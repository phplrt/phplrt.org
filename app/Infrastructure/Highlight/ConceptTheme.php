<?php

declare(strict_types=1);

namespace App\Infrastructure\Highlight;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Themes\EscapesWebTheme;
use Tempest\Highlight\Tokens\TokenType;
use Tempest\Highlight\Tokens\TokenTypeEnum;
use Tempest\Highlight\WebTheme;

/**
 * Renders highlighted code in the vocabulary the site's stylesheet speaks.
 *
 * The bundled {@see \Tempest\Highlight\Themes\CssTheme} emits "hl-*" classes
 * inside "<pre data-lang=... class=notranslate>". The design sheet instead
 * defines "c-*" token colours on a ".code" slab (with a light ".code--paper"
 * variant), so the mapping happens here rather than by duplicating every
 * colour under a second set of selectors.
 */
final readonly class ConceptTheme implements WebTheme
{
    use EscapesWebTheme;

    public function before(TokenType $tokenType): string
    {
        if ($tokenType === TokenTypeEnum::HIDDEN) {
            return '<span style="display: none">';
        }

        // An injection only groups tokens that are already coloured (a nested
        // language, a parameter list); giving it a colour of its own would
        // repaint its children.
        if ($tokenType === TokenTypeEnum::INJECTION) {
            return '<span>';
        }

        return '<span class="' . $this->getClass($tokenType) . '">';
    }

    public function after(TokenType $tokenType): string
    {
        return '</span>';
    }

    public function preBefore(Highlighter $highlighter): string
    {
        $language = $highlighter->getCurrentLanguage()?->getName() ?? 'txt';

        return '<pre class="code" data-lang="' . \htmlspecialchars($language, \ENT_QUOTES) . '">';
    }

    public function preAfter(Highlighter $highlighter): string
    {
        return '</pre>';
    }

    /**
     * @return non-empty-string
     */
    private function getClass(TokenType $tokenType): string
    {
        return match ($tokenType) {
            TokenTypeEnum::COMMENT => 'c-cm',
            TokenTypeEnum::KEYWORD => 'c-kw',
            TokenTypeEnum::TYPE, TokenTypeEnum::GENERIC => 'c-cls',
            TokenTypeEnum::VALUE => 'c-str',
            TokenTypeEnum::VARIABLE => 'c-var',
            TokenTypeEnum::PROPERTY => 'c-fn',
            TokenTypeEnum::ATTRIBUTE => 'c-tok',
            TokenTypeEnum::NUMBER, TokenTypeEnum::LITERAL => 'c-num',
            TokenTypeEnum::OPERATOR => 'c-op',
            // Anything the sheet has no colour for (custom token types added
            // by a future language) keeps the library's own name.
            default => 'hl-' . $tokenType->getValue(),
        };
    }
}
