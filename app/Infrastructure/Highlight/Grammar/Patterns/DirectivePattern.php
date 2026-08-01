<?php

declare(strict_types=1);

namespace App\Infrastructure\Highlight\Grammar\Patterns;

use Override;
use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenType;
use Tempest\Highlight\Tokens\TokenTypeEnum;

/**
 * The word a directive opens with: "%pragma", "%token", "%skip", and the rest.
 */
final readonly class DirectivePattern implements Pattern
{
    use IsPattern;

    /**
     * @param non-empty-list<non-empty-string> $directives Names without "%"
     */
    public function __construct(
        private array $directives,
    ) {}

    #[Override]
    public function getPattern(): string
    {
        return '/^\h*+(?<match>%(?:' . \implode('|', $this->directives) . '))\b/m';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::KEYWORD;
    }
}
