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
 * A token written as an expression where it is used rather than declared,
 * surrounded by a pair of slashes (PP3 only): "/[0-9]++/".
 *
 * A comment opens with a pair of slashes too, and is matched before this one so
 * that it wins whenever both describe the same span.
 */
#[PatternTest(input: 'Terminal : /[0-9]++/ ;', output: '/[0-9]++/')]
final readonly class InlineRegexPattern implements Pattern
{
    use IsPattern;

    #[Override]
    public function getPattern(): string
    {
        return '/(?<match>\/(?:[^\/\\\\\r\n]|\\\\.)*+\/)/';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::VALUE;
    }
}
