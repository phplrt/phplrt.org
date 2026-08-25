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
 * The expression a token is recognized by.
 *
 * It is a raw regexp written without any delimiters and cannot contain a space,
 * so everything up to the next one belongs to it - painted as a single value so
 * that the patterns of the grammar itself do not take it apart.
 */
#[PatternTest(input: '%skip T_WHITESPACE \s++', output: '\s++')]
final readonly class TokenDefinitionValuePattern implements Pattern
{
    use IsPattern;

    #[Override]
    public function getPattern(): string
    {
        return '/^\h*+%(?:token|skip|fragment)\h++(?:(?:[a-zA-Z_]\w*+|\*):)?[a-zA-Z_]\w*+\h++(?<match>\S++)/m';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::VALUE;
    }
}
