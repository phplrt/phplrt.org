<?php

declare(strict_types=1);

namespace App\Application\Controller;

use Highlight\Highlighter;
use Local\MathParser\AstDumper;
use Local\MathParser\MathParser;
use PhpParser\Node\Stmt\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[Route(path: '/math/parse.json', methods: ['POST'])]
final readonly class MathController
{
    public function __construct(
        private Environment $view,
        private Highlighter $hl,
    ) {}

    public function __invoke(MathParser $parser, AstDumper $dumper, Request $request): Response
    {
        try {
            /** @var Expression $ast */
            $ast = $parser->parse((string)$request->getContent());

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