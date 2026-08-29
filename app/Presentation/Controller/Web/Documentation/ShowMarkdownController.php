<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Web\Documentation;

use App\Domain\Documentation\Document;
use App\Domain\Documentation\PageRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Serves a documentation page as the markdown it was written in, which is what
 * "llms.txt" and "llms-full.txt" point a model at.
 *
 * The address is the one {@see ShowController} answers with an ".md" suffix, so
 * a reader that has the html address can reach the source by appending three
 * characters to it.
 */
#[AsController]
#[Route(
    path: '/docs/{path}.md',
    name: 'docs.show.md',
    requirements: ['path' => '[\w\-\d/\.]+'],
    methods: ['GET'],
)]
final readonly class ShowMarkdownController
{
    public function __construct(
        private PageRepositoryInterface $docs,
    ) {}

    public function __invoke(string $path): Response
    {
        $page = $this->docs->findByPath($path);

        // A "link" page is a menu entry pointing somewhere else entirely and
        // carries no source of its own, so there is nothing to hand back.
        if (!$page instanceof Document) {
            return $this->response(\sprintf("# Not Found\n\nThere is no page at \"%s\".\n", $path), 404);
        }

        $source = \str_replace("\r\n", "\n", $page->getContent()->getRawContent());

        return $this->response(\rtrim($source) . "\n");
    }

    /**
     * @param int<100, 599> $status
     */
    private function response(string $content, int $status = 200): Response
    {
        return new Response($content, $status, [
            'Content-Type' => 'text/markdown; charset=utf-8',
        ]);
    }
}
