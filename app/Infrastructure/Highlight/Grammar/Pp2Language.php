<?php

declare(strict_types=1);

namespace App\Infrastructure\Highlight\Grammar;

use App\Infrastructure\Highlight\Grammar\Patterns\ClassReducerPattern;
use Override;

/**
 * The grammar format phplrt 3.x read, still read by phplrt v4.
 *
 * It knows nothing of "%lexer" and writes an inline pattern as a string, so on
 * top of {@see GrammarLanguage} only the reducer written as the name of a class
 * is left to add.
 *
 * @link https://github.com/phplrt/phplrt/blob/master/libs/components/compiler/resources/pp2.pp3
 */
final class Pp2Language extends GrammarLanguage
{
    #[Override]
    public function getName(): string
    {
        return 'pp2';
    }

    #[Override]
    protected function getDirectives(): array
    {
        return ['pragma', 'include', 'token', 'skip'];
    }

    #[Override]
    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),
            new ClassReducerPattern(),
        ];
    }
}
