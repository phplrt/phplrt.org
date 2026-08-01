<?php

declare(strict_types=1);

namespace App\Infrastructure\Highlight\Grammar\Patterns;

use Override;
use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\PatternTest;
use Tempest\Highlight\Tokens\TokenType;
use Tempest\Highlight\Tokens\TokenTypeEnum;

/**
 * A token written where it is used rather than declared: "true".
 *
 * PP2 writes an inline pattern the same way, so a string covers both. It never
 * spans a line break, which keeps a stray quote from painting the rest of the
 * file.
 */
#[PatternTest(input: 'Terminal : "true" ;', output: '"true"')]
final readonly class InlineStringPattern implements Pattern
{
    use IsPattern;

    #[Override]
    public function getPattern(): string
    {
        return '/(?<match>"[^"\\\\\r\n]*+(?:\\\\.[^"\\\\\r\n]*+)*+")/';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::VALUE;
    }
}
