<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Web\Documentation;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsController, Route(path: '/docs', name: 'docs')]
final readonly class IndexController
{
    public function __construct(
        private UrlGeneratorInterface $routes,
    ) {}

    public function __invoke(): Response
    {
        return new RedirectResponse($this->routes->generate('docs.show', [
            'path' => 'guide/introduction',
        ]));
    }
}
