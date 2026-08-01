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
 * What a token does besides being read, written after the arrow closing its
 * declaration:
 * - %token T_QUOTE " -> state(string), channel(quotes)   (PP3)
 * - %token T_QUOTE " -> string                           (PP2)
 */
#[PatternTest(input: '%token T_QUOTE " -> state(string)', output: '-> state(string)')]
final readonly class TokenDefinitionActionPattern implements Pattern
{
    use IsPattern;

    #[Override]
    public function getPattern(): string
    {
        return '/^\h*+%(?:token|skip)\h++\S++\h++\S++\h++(?<match>->[^\r\n]*+)/m';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::ATTRIBUTE;
    }
}
