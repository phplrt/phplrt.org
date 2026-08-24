<?php

declare(strict_types=1);

namespace App\Infrastructure\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class DocumentationExtension extends AbstractExtension
{
    public function __construct(
        /**
         * @var array<non-empty-string, non-empty-string>
         */
        private readonly array $pages,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('docs', $this->path(...)),
        ];
    }

    /**
     * @param non-empty-string $alias
     * @return non-empty-string
     */
    public function path(string $alias): string
    {
        return $this->pages[$alias] ?? throw new \InvalidArgumentException(\sprintf(
            'Unknown documentation page "%s", expected one of: %s',
            $alias,
            \implode(', ', \array_keys($this->pages)),
        ));
    }
}
