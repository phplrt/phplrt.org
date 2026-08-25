<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Web\Seo;

use App\Domain\Documentation\Document;
use App\Domain\Documentation\MenuRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsController, Route(path: '/sitemap.xml', name: 'sitemap', methods: ['GET'])]
final readonly class SitemapController
{
    /**
     * How long a crawler may reuse the response, in seconds.
     */
    private const int TTL = 3600;

    public function __construct(
        private MenuRepositoryInterface $menus,
        private UrlGeneratorInterface $routes,
    ) {}

    public function __invoke(): Response
    {
        $response = new Response($this->getSitemap(), Response::HTTP_OK, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);

        return $response->setPublic()
            ->setMaxAge(self::TTL);
    }

    private function getSitemap(): string
    {
        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');

        $xml->startElement('urlset');
        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($this->getUrls() as $url) {
            $xml->startElement('url');
            $xml->writeElement('loc', $url);
            $xml->endElement();
        }

        $xml->endElement();
        $xml->endDocument();

        return $xml->outputMemory();
    }

    /**
     * @return iterable<array-key, string>
     */
    private function getUrls(): iterable
    {
        yield $this->routes->generate('home', [], UrlGeneratorInterface::ABSOLUTE_URL);

        foreach ($this->menus->findAll() as $menu) {
            foreach ($menu->getPages() as $page) {
                if (!$page instanceof Document) {
                    continue;
                }

                yield $this->routes->generate('docs.show', [
                    'path' => $page->getUrl(),
                ], UrlGeneratorInterface::ABSOLUTE_URL);
            }
        }
    }
}
