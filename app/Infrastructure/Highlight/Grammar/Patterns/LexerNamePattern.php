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
 * The state a hand written lexer is bound to (PP3 only):
 * "%lexer php -> { new \App\Lexer\PhpBlockLexer() }".
 */
#[PatternTest(input: '%lexer php -> { new Lexer() }', output: 'php')]
final readonly class LexerNamePattern implements Pattern
{
    use IsPattern;

    #[Override]
    public function getPattern(): string
    {
        return '/^\h*+%lexer\h++(?<match>[a-zA-Z_]\w*+)/m';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::TYPE;
    }
}
