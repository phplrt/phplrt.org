<?php

declare(strict_types=1);

namespace App\Presentation\Request\DTO\Documentation;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class SearchRequestDTO
{
    /**
     * @param non-empty-string $query
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 64, maxMessage: 'Request query is too long')]
        public string $query,
    ) {}
}
