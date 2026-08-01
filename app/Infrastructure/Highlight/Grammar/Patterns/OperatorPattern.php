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
 * Everything holding a rule together: the alternation, the quantifiers, the
 * brackets a token reference is written between, the arrow of a reducer, the
 * three spellings PP2 separates a rule with, and the "#" it keeps one by.
 */
#[PatternTest(input: 'Sum : A() | B() ;', output: ':')]
final readonly class OperatorPattern implements Pattern
{
    use IsPattern;

    #[Override]
    public function getPattern(): string
    {
        return '/(?<match>::=|::|->|[|&!?+*(){}<>,;:=#])/';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::OPERATOR;
    }
}
