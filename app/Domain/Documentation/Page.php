<?php

namespace App\Domain\Documentation;

use App\Domain\Shared\CreatedDateProvider;
use App\Domain\Shared\CreatedDateProviderInterface;
use App\Domain\Shared\IdentifiableInterface;
use App\Domain\Shared\UpdatedDateProvider;
use App\Domain\Shared\UpdatedDateProviderInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'pages')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'document' => Document::class,
    'link' => Link::class,
])]
#[ORM\Index(columns: ['url'], name: 'page_url_idx')]
#[ORM\Index(columns: ['sorting_order'], name: 'page_sorting_order_idx')]
abstract class Page implements
    IdentifiableInterface,
    CreatedDateProviderInterface,
    UpdatedDateProviderInterface
{
    use CreatedDateProvider;
    use UpdatedDateProvider;

    /**
     * @psalm-readonly-allow-private-mutation
     */
    #[ORM\Id]
    #[ORM\Column(type: PageId::class)]
    private PageId $id;

    /**
     * @psalm-readonly-allow-private-mutation
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $url;

    /**
     * @psalm-readonly-allow-private-mutation
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    /**
     * @var int<0, 32767>
     */
    #[ORM\Column(name: 'sorting_order', type: 'smallint')]
    private int $order = 0;

    /**
     * @psalm-readonly-allow-private-mutation
     */
    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'pages')]
    #[ORM\JoinColumn(name: 'menu_id', referencedColumnName: 'id')]
    private Menu $menu;

    /**
     * @param non-empty-string $url
     */
    public function __construct(
        Menu $menu,
        string $url,
        string $title,
        ?PageId $id = null,
    ) {
        $this->menu = $menu;
        $this->url = $url;
        $this->title = $title;
        $this->id = $id ?? PageId::fromNamespace(static::class);


        $pages = $menu->getPages();
        if (!$pages->contains($this)) {
            $pages->add($this);
        }
    }

    /**
     * @api
     */
    public function getId(): PageId
    {
        return $this->id;
    }

    /**
     * @api
     */
    public function getMenu(): Menu
    {
        return $this->menu;
    }

    /**
     * @api
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @api
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param int<0, 32767> $value
     */
    public function setOrder(int $value): void
    {
        $this->order = $value;
    }

    /**
     * @return non-empty-string
     */
    abstract public function getType(): string;
}
