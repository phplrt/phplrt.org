<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Web;

use Highlight\Highlighter;
use Local\MathParser\AstDumper;
use Local\MathParser\MathParser;
use PhpParser\Node\Stmt\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[Route(path: '/math/parse', methods: ['POST'])]
final readonly class MathController
{
    public function __construct(
        private Environment $view,
        private Highlighter $hl,
    ) {}

    public function __invoke(MathParser $parser, AstDumper $dumper, Request $request): Response
    {
        $stream = $request->getContent(true);

        $expression = \fread($stream, 1024);

        if (\is_string(\fread($stream, 1))) {
            $result = $this->view->render('page/home/highlight-error.html.twig', [
                'message' => 'Request too looooooooong :3',
            ]);

            return new Response($result, Response::HTTP_REQUEST_URI_TOO_LONG);
        }

        try {
            /** @var Expression $ast */
            $ast = $parser->parse($expression);

            $result = $this->view->render('page/home/highlight.html.twig', [
                'code' => $dumper->highlight($this->hl, $ast, 'Local\\MathParser'),
                'result' => $ast->eval(),
            ]);
        } catch (\Throwable $e) {
            $result = $this->view->render('page/home/highlight-error.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }

        return new Response($result);
    }
}
