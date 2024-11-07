<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Api;

use App\Domain\Documentation\Search;
use App\Presentation\Request\Attribute\Body;
use App\Presentation\Request\DTO\Documentation\SearchRequestDTO;
use App\Presentation\Response\Transformer\Search\SearchItemListTransformer;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/search.json', methods: ['POST'], stateless: true)]
final readonly class SearchController
{
    public function __construct(
        private SearchItemListTransformer $transformer,
        private Search $search,
    ) {}

    public function __invoke(#[Body] SearchRequestDTO $dto): array
    {
        return $this->transformer->transform(
            $this->search->findAllByQuery($dto->query),
        );
    }
}
