<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Api\Search;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class SearchRequestDTO
{
    /**
     * @param non-empty-string $query
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 24, maxMessage: 'Request query is too long')]
        public string $query,
    ) {}
}
