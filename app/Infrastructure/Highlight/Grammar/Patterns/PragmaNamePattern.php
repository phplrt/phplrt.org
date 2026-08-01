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
 * The setting a pragma changes: "%pragma lexer.pcre.flag u".
 */
#[PatternTest(input: '%pragma lexer.pcre.flag u', output: 'lexer.pcre.flag')]
final readonly class PragmaNamePattern implements Pattern
{
    use IsPattern;

    #[Override]
    public function getPattern(): string
    {
        return '/^\h*+%pragma\h++(?<match>[a-zA-Z_][\w.]*+)/m';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::PROPERTY;
    }
}
