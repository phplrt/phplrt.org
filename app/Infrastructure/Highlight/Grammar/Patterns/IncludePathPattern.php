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
 * The file a grammar pulls in: "%include grammar/lexemes".
 */
#[PatternTest(input: '%include grammar/lexemes', output: 'grammar/lexemes')]
final readonly class IncludePathPattern implements Pattern
{
    use IsPattern;

    #[Override]
    public function getPattern(): string
    {
        return '/^\h*+%include\h++(?<match>\S++)/m';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::VALUE;
    }
}
