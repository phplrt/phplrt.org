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
 * The name a rule is declared under.
 *
 * A rule opens a line and is followed by what separates it from what it
 * recognizes - ":" in both formats, plus "::=" and "=" in PP2, or the arrow of
 * a reducer in either of them. Which is usually written on the line below, so
 * the separator is looked for across the break as well. The "#" of a rule PP2
 * keeps by name is left to {@see OperatorPattern}.
 */
#[PatternTest(input: 'Sum : Number() ;', output: 'Sum')]
#[PatternTest(input: "Sum\n  : Number()\n  ;", output: 'Sum')]
final readonly class RuleDefinitionPattern implements Pattern
{
    use IsPattern;

    #[Override]
    public function getPattern(): string
    {
        return '/^\h*+#?+(?<match>\\\\?+[a-zA-Z_]\w*+(?:\\\\[a-zA-Z_]\w*+)*+)'
            . '(?=\s*+(?:::=|->|[:=](?![:=>])))/m';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::PROPERTY;
    }
}
