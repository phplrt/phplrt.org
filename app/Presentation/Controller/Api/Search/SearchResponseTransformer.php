<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Api\Search;

use App\Domain\Documentation\Search\Result;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @template-extends ResponseTransformer<iterable<Result>, list<SearchItemResponseDTO>>
 */
final readonly class SearchResponseTransformer extends ResponseTransformer
{
    public function __construct(
        private SearchItemResponseTransformer $compiler,
    ) {}

    public function transform(mixed $entry): array
    {
        assert(\is_iterable($entry));

        $result = [];

        /** @var Result $result */
        foreach ($entry as $item) {
            $result[] = $this->compiler->transform(
                entry: $item->index,
                occurrences: $item->occurrences,
            );
        }

        return $result;
    }
}
