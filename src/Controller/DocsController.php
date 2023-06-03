<?php

/**
 * This file is part of phplrt.org package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\NotFound;
use App\Entity\SearchIndex;
use App\Repository\DocumentationRepository;
use App\Repository\MenuRepository;
use App\Repository\SearchIndexRepository;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class DocsController extends Controller
{
    /**
     * @var MenuRepository
     */
    private MenuRepository $menu;

    /**
     * @var DocumentationRepository
     */
    private DocumentationRepository $docs;

    /**
     * @param Environment $view
     * @param MenuRepository $menu
     * @param DocumentationRepository $docs
     */
    public function __construct(Environment $view, MenuRepository $menu, DocumentationRepository $docs)
    {
        parent::__construct($view);

        $this->menu = $menu;
        $this->docs = $docs;
    }

    #[Route(
        path: '/docs/{path}',
        requirements: ['path' => '[\w\-\d/]+'],
        defaults: ['path' => ''],
    )]
    public function show(string $path): Response
    {
        $page = $this->docs->findByPath($path);

        $result = $this->view->render('page/docs.html.twig', [
            'menu'    => $this->menu->findAll(),
            'current' => $path,
            'page'    => $page ?? new NotFound(),
        ]);

        return new Response($result, $page ? 200 : 404);
    }

    #[Route(path: '/docs/search.json', methods: ['POST'])]
    public function search(Request $request, SearchIndexRepository $search): JsonResponse
    {
        $queries = $search->getQueries((string)$request->get('query', ''));
        $items = $search->searchByWords($queries, 6);

        return new JsonResponse(
            $this->indexToJson($queries, $items)
        );
    }

    /**
     * @param array<string> $queries
     * @param array<SearchIndex> $indexes
     * @return array
     */
    private function indexToJson(array $queries, array $indexes): array
    {
        $result = [];

        foreach ($indexes as $index) {
            $page = $index->getPage();

            $result[] = [
                'title' => $index->getTitle(),
                'url'   => '/docs/' . $page->getUrl() . '#' . Str::slug($index->getTitle()),
                'page'  => $page->getTitle(),
                'found' => $index->getHighlights($queries),
            ];
        }

        return $result;
    }
}
