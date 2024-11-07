<?php

declare(strict_types=1);

namespace App\Domain\Documentation;

use Doctrine\Persistence\ObjectManager;

final readonly class Synchronizer
{
    /**
     * @psalm-taint-sink file $directory
     * @param non-empty-string $directory
     */
    public function __construct(
        private string $directory,
        private MenuRepositoryInterface $menus,
        private PageRepositoryInterface $pages,
        private ObjectManager $em,
    ) {}

    public function truncate(): void
    {
        foreach ($this->menus->findAll() as $menu) {
            $this->em->remove($menu);
        }

        foreach ($this->pages->findAll() as $page) {
            $this->em->remove($page);
        }

        $this->em->flush();
    }

    public function sync(): void
    {
        $menuOrder = 0;
        foreach ($this->getManifest() as $category => $pages) {
            $menu = new Menu($category);
            $menu->setOrder($menuOrder++);

            $this->em->persist($menu);
            $this->em->flush();

            $pageOrder = 0;
            foreach ($pages as $title => $path) {
                $page = $this->getPageByPath($menu, $title, $path);
                $page->setOrder($pageOrder++);

                $this->em->persist($page);
                $this->em->flush();
            }
        }

        $this->em->flush();
    }

    /**
     * @return array<non-empty-string, array<non-empty-string, non-empty-string>>
     * @throws \JsonException
     */
    private function getManifest(): array
    {
        $pathname = $this->directory . '/manifest.json';

        if (!\is_file($pathname)) {
            throw new \InvalidArgumentException(\sprintf(
                'Manifest file "%s" not found',
                $pathname,
            ));
        }

        $contents = \file_get_contents($pathname);

        /** @var array<non-empty-string, array<non-empty-string, non-empty-string>> */
        return (array) \json_decode($contents, true, 512, \JSON_THROW_ON_ERROR);
    }

    private function getPageByPath(Menu $menu, string $title, string $path): Page
    {
        $pathname = $this->directory . '/' . $path . '.md';

        if (!\is_file($pathname)) {
            return new Link($menu, $path, $title);
        }

        $page = new Document($menu, $path, $title);

        $content = $page->getContent();
        $content->update(\file_get_contents($pathname));

        return $page;
    }
}
