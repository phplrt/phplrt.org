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
 * A rule used by another one, always written with a pair of empty parentheses:
 * "Number()", "\App\Node\Sum()".
 */
#[PatternTest(input: 'Sum : \App\Node\Number() ;', output: '\App\Node\Number')]
final readonly class RuleReferencePattern implements Pattern
{
    use IsPattern;

    #[Override]
    public function getPattern(): string
    {
        return '/(?<match>\\\\?+[a-zA-Z_]\w*+(?:\\\\[a-zA-Z_]\w*+)*+)\(\)/';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::PROPERTY;
    }
}
