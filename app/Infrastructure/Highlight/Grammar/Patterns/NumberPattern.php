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
 * How many times a statement repeats: the numbers of "A{2,8}".
 */
#[PatternTest(input: 'Ranges : <T_NUMBER>{2,8} ;', output: '2')]
final readonly class NumberPattern implements Pattern
{
    use IsPattern;

    #[Override]
    public function getPattern(): string
    {
        return '/\b(?<match>\d++)\b/';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::NUMBER;
    }
}
