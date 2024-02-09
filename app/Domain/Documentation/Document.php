<?php

namespace App\Domain\Documentation;

use Doctrine\ORM\Mapping as ORM;

/**
 * @final
 */
#[ORM\Entity]
class Document extends Page
{
    /**
     * @psalm-readonly-allow-private-mutation
     */
    #[ORM\Embedded(class: Content::class, columnPrefix: 'content_')]
    private Content $content;

    /**
     * @param non-empty-string $url
     */
    public function __construct(
        Menu $menu,
        string $url,
        string $title,
        string|\Stringable $content = '',
        ?PageId $id = null,
    ) {
        if (!$content instanceof Content) {
            $content = new Content((string) $content);
        }

        $this->content = $content;

        parent::__construct($menu, $url, $title, $id);
    }

    public function getContent(): Content
    {
        return $this->content;
    }

    public function getType(): string
    {
        return 'document';
    }
}
