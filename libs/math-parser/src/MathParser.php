<?php

/**
 * This file is part of phplrt.org package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\MathParser;

use Phplrt\Contracts\Parser\ParserInterface;
use Phplrt\Lexer\Lexer;
use Phplrt\Parser\BuilderInterface;
use Phplrt\Parser\ContextInterface;
use Phplrt\Parser\Parser;

class MathParser implements ParserInterface, BuilderInterface
{
    /**
     * @var ParserInterface
     */
    private ParserInterface $runtime;

    /**
     * @var array
     */
    private array $reducers;

    public function __construct()
    {
        $grammar = require __DIR__ . '/../resources/math.php';

        $this->reducers = $grammar['reducers'];

        $lexer = new Lexer($grammar['tokens']['default'], $grammar['skip']);

        $this->runtime = new Parser($lexer, $grammar['grammar'], [
            Parser::CONFIG_INITIAL_RULE => $grammar['initial'],
            Parser::CONFIG_AST_BUILDER  => $this,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function build(ContextInterface $context, $result)
    {
        $state = $context->getState();

        if (isset($this->reducers[$state])) {
            return $this->reducers[$state]($context, $result);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function parse($source, array $options = []): iterable
    {
        return $this->runtime->parse($source, $options);
    }
}
