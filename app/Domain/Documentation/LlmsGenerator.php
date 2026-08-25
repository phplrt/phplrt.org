<?php

declare(strict_types=1);

namespace App\Domain\Documentation;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Writes the two files a language model is pointed at:
 *
 *  - "llms.txt" is an index in the format described at https://llmstxt.org:
 *    an H1, a summary in a blockquote, some prose, and then one H2 per section
 *    holding a list of links;
 *  - "llms-full.txt" is the same documentation with every body inlined, so a
 *    model can be handed the whole corpus instead of crawling for it.
 *
 * Both are static files under "public", which means they are only correct for
 * as long as somebody regenerates them. That somebody is {@see \App\Presentation\Command\DocsUpdateCommand},
 * which runs this right after it has rebuilt the pages.
 *
 * Because this runs on the command line there is no request to take a hostname
 * from, so the addresses come from the router's "default_uri" instead. It is
 * set in "config/packages/routing.yaml".
 */
final readonly class LlmsGenerator
{
    /**
     * The longest note put after a link in the index, in characters. Anything
     * longer is cut at a word boundary.
     */
    private const int SUMMARY_LENGTH = 200;

    /**
     * @psalm-taint-sink file $directory
     * @param non-empty-string $directory
     */
    public function __construct(
        private string $directory,
        private MenuRepositoryInterface $menus,
        private UrlGeneratorInterface $routes,
    ) {}

    /**
     * @return list<non-empty-string> pathnames of the files that were written
     */
    public function generate(): array
    {
        return [
            $this->write('llms.txt', $this->getIndex()),
            $this->write('llms-full.txt', $this->getDocumentation()),
        ];
    }

    /**
     * @param non-empty-string $name
     * @return non-empty-string
     */
    private function write(string $name, string $content): string
    {
        $pathname = $this->directory . '/' . $name;

        if (!\is_dir($this->directory)) {
            throw new \InvalidArgumentException(\sprintf(
                'Output directory "%s" does not exist',
                $this->directory,
            ));
        }

        if (\file_put_contents($pathname, $content) === false) {
            throw new \RuntimeException(\sprintf('Could not write "%s"', $pathname));
        }

        return $pathname;
    }

    private function getIndex(): string
    {
        $out = [
            '# phplrt',
            '',
            '> phplrt (PHP Language Recognition Tool) is a set of PHP libraries for reading '
                . 'source code: a lexer, a PEG parser, a grammar compiler and an error printer. '
                . 'You describe a format once in a grammar file, and phplrt turns that '
                . 'description into a lexer that cuts the text into tokens and a parser that '
                . 'checks their order and builds whatever result you asked for.',
            '',
            'Requires PHP 8.4 or above. Published under the MIT licence.',
            '',
            'The library is split in half. The compiler reads `.pp3` grammar files and writes',
            'a parser as plain PHP, which is a job for development time. The runtime, meaning',
            'the lexer, the parser and the source reader, is the only half that ships to',
            'production.',
            '',
            \sprintf(
                'The whole documentation is also available as one file: %s',
                $this->getSiteUrl('llms-full.txt'),
            ),
        ];

        foreach ($this->menus->findAll() as $menu) {
            $section = $this->getSection($menu);

            if ($section === []) {
                continue;
            }

            $out[] = '';
            $out[] = \sprintf('## %s', $menu->getTitle());
            $out[] = '';

            foreach ($section as $line) {
                $out[] = $line;
            }
        }

        return \implode("\n", $out) . "\n";
    }

    private function getDocumentation(): string
    {
        $out = [
            '# phplrt documentation',
            '',
            \sprintf(
                '> Every documentation page of phplrt, in the order the site lists them.'
                    . ' The same pages as links, with a summary each, are at %s.',
                $this->getSiteUrl('llms.txt'),
            ),
        ];

        foreach ($this->menus->findAll() as $menu) {
            foreach ($menu->getPages() as $page) {
                // A "link" page points outside the site and has no body of its
                // own, so there is nothing here to inline.
                if (!$page instanceof Document) {
                    continue;
                }

                $source = \trim($page->getContent()->getRawContent());

                if ($source === '') {
                    continue;
                }

                $out[] = '';
                $out[] = '---';
                $out[] = '';
                $out[] = \sprintf('Section: %s', $menu->getTitle());
                $out[] = \sprintf('Source: %s', $this->getPageUrl($page));
                $out[] = '';
                $out[] = \str_replace("\r\n", "\n", $source);
            }
        }

        return \implode("\n", $out) . "\n";
    }

    /**
     * @return list<non-empty-string>
     */
    private function getSection(Menu $menu): array
    {
        $lines = [];

        foreach ($menu->getPages() as $page) {
            $summary = $page instanceof Document
                ? $this->getSummary($page)
                : null;

            $lines[] = \sprintf(
                '- [%s](%s)%s',
                $page->getTitle(),
                $this->getPageUrl($page),
                $summary === null ? '' : ': ' . $summary,
            );
        }

        return $lines;
    }

    /**
     * @param non-empty-string $path
     * @return non-empty-string
     */
    private function getSiteUrl(string $path): string
    {
        $home = $this->routes->generate('home', [], UrlGeneratorInterface::ABSOLUTE_URL);

        return \rtrim($home, '/') . '/' . $path;
    }

    /**
     * The address a menu entry is read at.
     */
    private function getPageUrl(Page $page): string
    {
        // A "link" page is a menu entry pointing somewhere else entirely, so
        // its url is already whole and must not be routed through /docs.
        if ($page instanceof Link) {
            return $page->getUrl();
        }

        return $this->routes->generate('docs.show', [
            'path' => $page->getUrl(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * Returns the first sentence of the first real paragraph of a page, or
     * {@see null} when the page opens with something that is not prose.
     *
     * @return non-empty-string|null
     */
    private function getSummary(Document $page): ?string
    {
        $paragraph = $this->getFirstParagraph($page->getContent()->getRawContent());

        if ($paragraph === null) {
            return null;
        }

        $summary = $this->getFirstSentence($this->toPlainText($paragraph));

        // A paragraph that introduces a code block ends in a colon, which
        // reads like a truncation once the code block is gone.
        $summary = \rtrim($summary, ':');

        return $summary === '' ? null : $summary;
    }

    /**
     * @return non-empty-string|null
     */
    private function getFirstParagraph(string $source): ?string
    {
        $source = \str_replace("\r\n", "\n", $source);

        // A fenced block may sit above the first paragraph, and a grammar or a
        // snippet never describes the page it opens.
        $source = \preg_replace('/^```.*?^```/ms', '', $source) ?? $source;

        foreach (\preg_split('/\n{2,}/', $source) ?: [] as $block) {
            $block = \trim($block);

            // Headings, quotes, tables, lists and indented code are structure
            // rather than the sentence describing the page.
            if ($block === '' || \preg_match('/^(?:[#>|]|[-*+]\x20|\d+\.\x20|\x20{4}|\t)/', $block) === 1) {
                continue;
            }

            return $block;
        }

        return null;
    }

    private function toPlainText(string $markdown): string
    {
        // Images carry nothing readable; links keep their text and lose the
        // address, which would otherwise be repeated inside the note.
        $markdown = \preg_replace('/!\[[^\]]*]\([^)]*\)/', '', $markdown) ?? $markdown;
        $markdown = \preg_replace('/\[([^\]]+)]\([^)]*\)/', '$1', $markdown) ?? $markdown;

        $markdown = \str_replace(['`', '**', '__'], '', $markdown);
        $markdown = \preg_replace('/(?<![\w*])[*_](?=\S)([^*_]+)(?<=\S)[*_](?![\w*])/u', '$1', $markdown)
            ?? $markdown;

        return \trim((string) \preg_replace('/\s+/u', ' ', $markdown));
    }

    private function getFirstSentence(string $text): string
    {
        // A full stop only ends a sentence when the next one starts, which is
        // what keeps "i.e." and "8.4" from cutting a note in half. The leading
        // length keeps a heading-like opening from being taken for a sentence.
        if (\preg_match('/^(.{20,}?[.!?])\s+["\'(\x{201C}]?\p{Lu}/u', $text, $matches) === 1) {
            $text = $matches[1];
        }

        if (\mb_strlen($text) <= self::SUMMARY_LENGTH) {
            return $text;
        }

        $cut = \mb_substr($text, 0, self::SUMMARY_LENGTH);
        $boundary = \mb_strrpos($cut, ' ');

        return \rtrim($boundary === false ? $cut : \mb_substr($cut, 0, $boundary), ' ,;:') . '…';
    }
}
