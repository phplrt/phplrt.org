<?php

declare(strict_types=1);

namespace Local\Twig\HighlightExtension;

use Local\Twig\HighlightExtension\Internal\HighlightTokenParser;
use Highlight\Highlighter;
use Twig\Extension\AbstractExtension;

final class HighlightExtension extends AbstractExtension
{
    /**
     * @param Highlighter $hl
     */
    public function __construct(
        private readonly Highlighter $hl
    ) {}

    /**
     * {@inheritDoc}
     */
    public function getTokenParsers(): array
    {
        return [new HighlightTokenParser($this->hl)];
    }
}
