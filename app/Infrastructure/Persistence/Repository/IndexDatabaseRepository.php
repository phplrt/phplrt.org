<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Documentation\Search;
use App\Domain\Documentation\Search\Index;
use App\Domain\Documentation\Search\IndexRepositoryInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\UnicodeString;

/**
 * @template-extends DatabaseRepository<Index>
 */
class IndexDatabaseRepository extends DatabaseRepository implements IndexRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Index::class);
    }

    /**
     * @param array<string> $queries
     * @param positive-int $limit
     * @return iterable<Index>
     */
    public function searchByWords(array $queries, int $limit = Search::DEFAULT_LIMIT): iterable
    {
        if ($queries === []) {
            return [];
        }

        $builder = $this->createQueryBuilder('idx');

        foreach ($queries as $q => $query) {
            $lower = (new UnicodeString($query))
                ->lower();

            $builder->orWhere('LOWER(idx.title) LIKE :query' . $q);
            $builder->setParameter('query' . $q, '%' . $lower . '%');
        }

        return $builder
            ->leftJoin('idx.document', 'page', Join::ON)
            ->orderBy('idx.level')
            //->groupBy('idx.page')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
