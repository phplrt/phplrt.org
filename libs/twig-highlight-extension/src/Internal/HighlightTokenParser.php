<?php

declare(strict_types=1);

namespace Local\Twig\HighlightExtension\Internal;

use Tempest\Highlight\Highlighter;
use Twig\Error\SyntaxError;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;
use Twig\TokenStream;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Local\Twig\HighlightExtension
 */
class HighlightTokenParser extends AbstractTokenParser
{
    /**
     * @param Highlighter $hl
     */
    public function __construct(
        private readonly Highlighter $hl
    ) {}

    /**
     * @param Token $token
     * @return Node
     * @throws SyntaxError
     */
    public function parse(Token $token): Node
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $value = $this->getCodeLanguage($stream);
        $startAt = $this->getGutterStart($stream);

        $stream->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideBlockEnd'], true);
        $stream->expect(Token::BLOCK_END_TYPE);

        return new HighlightedNode($this->hl, $value, $startAt, $body, $lineno, $this->getTag());
    }

    /**
     * @param TokenStream $stream
     * @return string
     * @throws SyntaxError
     */
    private function getCodeLanguage(TokenStream $stream): string
    {
        if ($stream->test(Token::BLOCK_END_TYPE)) {
            return 'php';
        }

        $expr = $this->parser->getExpressionParser()
            ->parseExpression()
        ;

        if (! $expr instanceof ConstantExpression) {
            $message = 'An escaping strategy must be a string or false.';

            throw new SyntaxError($message, $stream->getCurrent()
                ->getLine(), $stream->getSourceContext());
        }

        return $expr->getAttribute('value');
    }

    /**
     * Reads the optional "from <line>" suffix, which turns the line numbers on
     * and says which number the first line carries:
     *
     *      {% code 'php' %}            no numbers
     *      {% code 'php' from 1 %}     numbered, whole file
     *      {% code 'php' from 42 %}    numbered, an excerpt out of a bigger one
     *
     * The number is the same one {@see Highlighter::withGutter()} takes, and
     * the same one a markdown fence writes as ```php{42}.
     *
     * @param TokenStream $stream
     * @return int<1, max>|null
     * @throws SyntaxError
     */
    private function getGutterStart(TokenStream $stream): ?int
    {
        if (! $stream->nextIf(Token::NAME_TYPE, 'from')) {
            return null;
        }

        $expr = $this->parser->getExpressionParser()
            ->parseExpression()
        ;

        $value = $expr instanceof ConstantExpression
            ? $expr->getAttribute('value')
            : null;

        // A gutter is written into the markup while the template is compiled,
        // so the first line has to be known then: a variable would arrive too
        // late to be of any use.
        if (! \is_int($value) || $value < 1) {
            $message = 'The line the numbering starts from must be an integer literal of 1 or more.';

            throw new SyntaxError($message, $stream->getCurrent()
                ->getLine(), $stream->getSourceContext());
        }

        return $value;
    }

    public function decideBlockEnd(Token $token): bool
    {
        return $token->test('endcode');
    }

    /**
     * {@inheritDoc}
     */
    public function getTag(): string
    {
        return 'code';
    }
}
