<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Documentation\Menu;
use App\Domain\Documentation\MenuRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends DatabaseRepository<Menu>
 */
class MenuDatabaseRepository extends DatabaseRepository implements MenuRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function findAll(): iterable
    {
        return $this->findBy([], ['priority' => 'ASC']);
    }
}
