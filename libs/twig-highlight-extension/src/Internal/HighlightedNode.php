<?php

declare(strict_types=1);

namespace Local\Twig\HighlightExtension\Internal;

use Tempest\Highlight\Highlighter;
use Twig\Compiler;
use Twig\Node\Node;
use Twig\Node\TextNode;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Local\Twig\HighlightExtension
 */
final class HighlightedNode extends Node
{
    /**
     * @param Highlighter $hl
     * @param string $lang
     * @param Node $body
     * @param int $line
     * @param string $tag
     */
    public function __construct(
        private readonly Highlighter $hl,
        string $lang,
        Node $body,
        int $line,
        string $tag = 'highlight'
    )
    {
        parent::__construct(['body' => $body], ['lang' => $lang], $line, $tag);
    }

    /**
     * @param Compiler $compiler
     * @throws \Exception
     */
    public function compile(Compiler $compiler): void
    {
        $body = $this->getNode('body');

        if ($body instanceof TextNode) {
            $body->setAttribute('data',
                $this->render(
                    $this->getAttribute('lang'),
                    $body->getAttribute('data')
                )
            );

            $compiler->subcompile($body);
        }
    }

    /**
     * @param string $lang
     * @param string $code
     * @return string
     * @throws \Exception
     */
    private function render(string $lang, string $code): string
    {
        $highlighted = $this->hl->parse($code, $lang);

        // The highlighter falls back to a default language when the requested
        // one is not registered, so the actually applied name is read back.
        $applied = $this->hl->getCurrentLanguage()?->getName() ?? $lang;

        return '<code data-language="' . \htmlspecialchars($applied, \ENT_QUOTES) . '">' .
            \trim($highlighted) .
        '</code>';
    }
}
