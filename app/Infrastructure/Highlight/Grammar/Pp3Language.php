<?php

declare(strict_types=1);

namespace App\Infrastructure\Highlight\Grammar;

use App\Infrastructure\Highlight\Grammar\Patterns\InlineRegexPattern;
use App\Infrastructure\Highlight\Grammar\Patterns\LexerNamePattern;

/**
 * The grammar format phplrt v4 reads.
 *
 * On top of {@see GrammarLanguage} it adds the "%lexer" directive and the
 * inline patterns written between a pair of slashes.
 *
 * @link https://github.com/phplrt/phplrt/blob/master/libs/components/compiler/resources/pp3.pp3
 */
final class Pp3Language extends GrammarLanguage
{
    #[\Override]
    public function getName(): string
    {
        return 'pp3';
    }

    #[\Override]
    protected function getDirectives(): array
    {
        return ['pragma', 'include', 'token', 'skip', 'lexer', 'fragment'];
    }

    #[\Override]
    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),
            new LexerNamePattern(),
            new InlineRegexPattern(),
        ];
    }
}
