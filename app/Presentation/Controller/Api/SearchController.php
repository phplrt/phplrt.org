<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Api;

use App\Domain\Documentation\Search;
use App\Presentation\Controller\Api\Search\SearchResponseTransformer;
use App\Presentation\Controller\Api\Search\SearchRequestDTO;
use Local\HttpData\Attribute\MapBody;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController, Route(path: '/search.json', methods: ['POST'], stateless: true)]
final readonly class SearchController
{
    public function __construct(
        private SearchResponseTransformer $transformer,
        private Search $search,
    ) {}

    public function __invoke(#[MapBody] SearchRequestDTO $dto): array
    {
        return $this->transformer->transform(
            $this->search->findAllByQuery($dto->query),
        );
    }
}
