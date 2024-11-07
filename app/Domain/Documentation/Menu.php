<?php

namespace App\Domain\Documentation;

use App\Domain\Shared\CreatedDateProvider;
use App\Domain\Shared\CreatedDateProviderInterface;
use App\Domain\Shared\IdentifiableInterface;
use App\Domain\Shared\UpdatedDateProvider;
use App\Domain\Shared\UpdatedDateProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'menu')]
#[ORM\Index(name: 'menu_sorting_order_idx', columns: ['sorting_order'])]
class Menu implements
    IdentifiableInterface,
    CreatedDateProviderInterface,
    UpdatedDateProviderInterface
{
    use CreatedDateProvider;
    use UpdatedDateProvider;

    #[ORM\Id]
    #[ORM\Column(type: MenuId::class)]
    private MenuId $id;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    /**
     * @var int<0, 32767>
     */
    #[ORM\Column(name: 'sorting_order', type: 'smallint')]
    private int $order = 0;

    /**
     * @var Collection<array-key, Document>
     */
    #[ORM\OneToMany(targetEntity: Page::class, mappedBy: 'menu', cascade: ['ALL'], fetch: 'EAGER')]
    #[ORM\OrderBy(['order' => 'ASC'])]
    private Collection $pages;

    /**
     * @param non-empty-string $title
     */
    public function __construct(string $title)
    {
        $this->id = MenuId::fromNamespace(static::class);
        $this->title = $title;
        $this->pages = new ArrayCollection();
    }

    public function getId(): MenuId
    {
        return $this->id;
    }

    /**
     * @return Collection<array-key, Document>
     */
    public function getPages(): Collection
    {
        return $this->pages;
    }

    /**
     * @return non-empty-string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param non-empty-string $title
     */
    public function rename(string $title): void
    {
        assert($title !== '');

        $this->title = $title;
    }

    /**
     * @param int<0, 32767> $value
     */
    public function setOrder(int $value): void
    {
        $this->order = $value;
    }
}
