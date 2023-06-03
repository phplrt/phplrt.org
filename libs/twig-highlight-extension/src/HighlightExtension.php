<?php

/**
 * This file is part of phplrt.org package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Twig\HighlightExtension;

use App\Twig\HighlightExtension\Internal\HighlightTokenParser;
use Highlight\Highlighter;
use Twig\Extension\AbstractExtension;

final class HighlightExtension extends AbstractExtension
{
    /**
     * @param Highlighter $hl
     */
    public function __construct(
        private readonly Highlighter $hl
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getTokenParsers(): array
    {
        return [new HighlightTokenParser($this->hl)];
    }
}
