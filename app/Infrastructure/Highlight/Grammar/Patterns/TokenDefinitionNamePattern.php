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
 * The name a token is declared under, together with the state it belongs to:
 * - %token T_NAME [a-z]++
 * - %token string:T_QUOTE "
 * - %skip *:T_WHITESPACE \s++
 */
#[PatternTest(input: '%token string:T_QUOTE "', output: 'string:T_QUOTE')]
final readonly class TokenDefinitionNamePattern implements Pattern
{
    use IsPattern;

    #[Override]
    public function getPattern(): string
    {
        return '/^\h*+%(?:token|skip)\h++(?<match>(?:(?:[a-zA-Z_]\w*+|\*):)?[a-zA-Z_]\w*+)/m';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::TYPE;
    }
}
