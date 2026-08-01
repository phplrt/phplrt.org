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
 * What a pragma is set to: "%pragma root Grammar".
 */
#[PatternTest(input: '%pragma root Grammar', output: 'Grammar')]
final readonly class PragmaValuePattern implements Pattern
{
    use IsPattern;

    #[Override]
    public function getPattern(): string
    {
        return '/^\h*+%pragma\h++[a-zA-Z_][\w.]*+\h++(?<match>\S++)/m';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::VALUE;
    }
}
