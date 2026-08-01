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
 * A token given to the rule reading it: "<T_NAME>".
 */
#[PatternTest(input: 'Sum : <T_DIGIT> ;', output: 'T_DIGIT')]
final readonly class KeptTokenPattern implements Pattern
{
    use IsPattern;

    #[Override]
    public function getPattern(): string
    {
        return '/<(?<match>[a-zA-Z_]\w*+)>/';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::TYPE;
    }
}
