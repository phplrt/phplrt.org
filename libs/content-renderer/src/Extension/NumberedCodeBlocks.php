<?php

declare(strict_types=1);

namespace Local\ContentRenderer\Extension;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use Tempest\Highlight\CommonMark\CodeBlockRenderer;
use Tempest\Highlight\CommonMark\HighlightExtension;
use Tempest\Highlight\Highlighter;
use Tempest\Highlight\WebTheme;

/**
 * Numbers the lines of a fenced code block unless there is a reason not to.
 *
 * {@see CodeBlockRenderer}, the one this replaces, numbers a block only when
 * the fence asks for it by name — ```php{12}. That is the right default for a
 * library and the wrong one for a manual, where a reader is told to "look at
 * line 7" and has to count to find it. So the default is turned around here,
 * and the fence keeps every bit of its say:
 *
 *      ```php          numbered from 1
 *      ```php{12}      numbered from 12 — an excerpt out of a longer file
 *      ```php{0}       not numbered; there is no line zero, so the value is
 *                      free to mean "leave this one alone"
 *
 * Two kinds of block are left alone by default, because a number would be
 * noise rather than a landmark:
 *
 *   - a shell session. Nobody cites the third line of a terminal transcript,
 *     and the "$" already stands where the number would go.
 *   - anything one line long. "1" in front of a single line states what the
 *     reader can see.
 *
 * Both are defaults, not rules: an explicit {n} on the fence numbers such a
 * block anyway.
 *
 * Registered above {@see HighlightExtension}'s own renderer, which stays in
 * place for inline code.
 */
final readonly class NumberedCodeBlocks implements ExtensionInterface, NodeRendererInterface
{
    /**
     * The shell family, by every name the highlighter answers to — see
     * BashLanguage and TerminalLanguage in tempest/highlight.
     *
     * @var list<non-empty-string>
     */
    private const SHELL_LANGUAGES = ['bash', 'sh', 'shell', 'terminal', 'console', 'term'];

    /**
     * The shortest block still worth numbering.
     */
    private const MIN_NUMBERED_LINES = 2;

    public function __construct(
        private Highlighter $highlighter = new Highlighter(),
    ) {}

    public function register(EnvironmentBuilderInterface $environment): void
    {
        // The bundled renderer is registered at 10 and is not removed: this
        // one only has to be asked first.
        $environment->addRenderer(FencedCode::class, $this, 20);
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        if (!$node instanceof FencedCode) {
            throw new \InvalidArgumentException('Block must be an instance of ' . FencedCode::class);
        }

        $info = $node->getInfoWords()[0] ?? 'txt';

        \preg_match('/^(?<language>[\w]+)(\{(?<startAt>\d+)\})?/', $info, $matches);

        $language = $matches['language'] ?? 'txt';
        $code = $node->getLiteral();

        $startAt = $this->getGutterStart($matches['startAt'] ?? null, $language, $code);

        $highlighter = $startAt === null
            ? $this->highlighter
            : $this->highlighter->withGutter($startAt);

        $parsed = $highlighter->parse($code, $language);
        $theme = $highlighter->getTheme();

        if ($theme instanceof WebTheme) {
            return $theme->preBefore($highlighter) . $parsed . $theme->preAfter($highlighter);
        }

        return '<pre data-lang="' . $language . '" class="notranslate">' . $parsed . '</pre>';
    }

    /**
     * @param non-empty-string|null $requested the "{n}" written on the fence
     * @param string $language
     * @param string $code
     * @return int<1, max>|null the number the first line carries, or null when
     *         the block is not numbered
     */
    private function getGutterStart(?string $requested, string $language, string $code): ?int
    {
        // Whatever the fence says, it says.
        if ($requested !== null) {
            return ((int) $requested) ?: null;
        }

        if (\in_array(\strtolower($language), self::SHELL_LANGUAGES, true)) {
            return null;
        }

        return $this->countLines($code) < self::MIN_NUMBERED_LINES ? null : 1;
    }

    /**
     * Counts the lines the gutter would number — which is what
     * GutterInjection counts, trailing blank line and all.
     *
     * @param string $code
     * @return int<0, max>
     */
    private function countLines(string $code): int
    {
        $lines = \preg_split('/\R/u', \trim($code, "\n"));

        return $lines === false ? 0 : \count($lines);
    }
}
