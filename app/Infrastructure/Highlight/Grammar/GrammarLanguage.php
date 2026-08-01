<?php

declare(strict_types=1);

namespace App\Infrastructure\Highlight\Grammar;

use App\Infrastructure\Highlight\Grammar\Patterns\CommentPattern;
use App\Infrastructure\Highlight\Grammar\Patterns\DirectivePattern;
use App\Infrastructure\Highlight\Grammar\Patterns\IncludePathPattern;
use App\Infrastructure\Highlight\Grammar\Patterns\InlineStringPattern;
use App\Infrastructure\Highlight\Grammar\Patterns\KeptTokenPattern;
use App\Infrastructure\Highlight\Grammar\Patterns\NumberPattern;
use App\Infrastructure\Highlight\Grammar\Patterns\OperatorPattern;
use App\Infrastructure\Highlight\Grammar\Patterns\PragmaNamePattern;
use App\Infrastructure\Highlight\Grammar\Patterns\PragmaValuePattern;
use App\Infrastructure\Highlight\Grammar\Patterns\RuleDefinitionPattern;
use App\Infrastructure\Highlight\Grammar\Patterns\RuleReferencePattern;
use App\Infrastructure\Highlight\Grammar\Patterns\SkippedTokenPattern;
use App\Infrastructure\Highlight\Grammar\Patterns\TokenDefinitionActionPattern;
use App\Infrastructure\Highlight\Grammar\Patterns\TokenDefinitionNamePattern;
use App\Infrastructure\Highlight\Grammar\Patterns\TokenDefinitionValuePattern;
use Override;
use Tempest\Highlight\Languages\Base\BaseLanguage;

/**
 * Everything the PP2 and the PP3 formats spell the same way.
 *
 * Both are written of the same two halves: the "%" directives declaring what
 * the lexer reads, and the rules declaring what the parser recognizes. What
 * sets the formats apart is added by {@see Pp2Language} and {@see Pp3Language}.
 */
abstract class GrammarLanguage extends BaseLanguage
{
    /**
     * The directives the format is written with, without their "%" prefix.
     *
     * @return non-empty-list<non-empty-string>
     */
    abstract protected function getDirectives(): array;

    #[Override]
    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),

            // A directive occupies a line of its own, so each of its parts is
            // matched from the start of that line rather than on its own. That
            // also keeps the expression of a token (which is a raw regexp, and
            // looks like just about anything) painted as a single value instead
            // of being taken apart by the patterns below it.
            new DirectivePattern($this->getDirectives()),
            new TokenDefinitionNamePattern(),
            new TokenDefinitionValuePattern(),
            new TokenDefinitionActionPattern(),
            new PragmaNamePattern(),
            new PragmaValuePattern(),
            new IncludePathPattern(),

            new CommentPattern(),

            new RuleDefinitionPattern(),
            new KeptTokenPattern(),
            new SkippedTokenPattern(),
            new RuleReferencePattern(),
            new InlineStringPattern(),
            new NumberPattern(),
            new OperatorPattern(),
        ];
    }
}
