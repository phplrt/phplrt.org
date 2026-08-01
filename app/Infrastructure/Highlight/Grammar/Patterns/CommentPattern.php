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
 * A comment, of both the kinds the format is written with.
 *
 * The expression of a token is a raw regexp, and one recognizing a comment
 * opens the very same way a comment does:
 *
 * ```
 * %skip T_COMMENT  //[^\r\n]*+
 * %token T_DOC     /\*(.*?)\*\/
 * ```
 *
 * So a declaration is stepped over first - up to and including the expression,
 * which leaves a comment written after it on the same line still matched. The
 * "(*SKIP)(*FAIL)" pair is what tells the engine to resume there rather than to
 * try the alternatives from inside what was just stepped over.
 */
#[PatternTest(input: '%skip T_COMMENT //[^\r\n]*+ // trivia', output: '// trivia')]
final readonly class CommentPattern implements Pattern
{
    use IsPattern;

    #[Override]
    public function getPattern(): string
    {
        return '/^\h*+%(?:token|skip)\h++\S++\h++\S++(*SKIP)(*FAIL)'
            . '|(?<match>\/\*[\s\S]*?\*\/|\/\/[^\r\n]*+)/m';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::COMMENT;
    }
}
