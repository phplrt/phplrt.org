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
 * What a rule is turned into, written as the name of a class (PP2 only):
 * "Number -> \App\Ast\NumberNode".
 *
 * It is matched from the start of the rule so that an arrow written inside the
 * PHP of a reducer ("$children->value") is left alone.
 */
#[PatternTest(input: 'Number -> \App\Ast\NumberNode', output: '\App\Ast\NumberNode')]
final readonly class ClassReducerPattern implements Pattern
{
    use IsPattern;

    #[Override]
    public function getPattern(): string
    {
        return '/^\h*+#?+[\w\\\\]++\s*+->\s*+(?<match>\\\\?+[a-zA-Z_]\w*+(?:\\\\[a-zA-Z_]\w*+)*+)/m';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::TYPE;
    }
}
