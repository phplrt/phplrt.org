<?php

declare(strict_types=1);

use App\MathParser\Ast;

/**
 * @var array{
 *     initial: array-key,
 *     tokens: array{
 *         default: array<non-empty-string, non-empty-string>,
 *         ...
 *     },
 *     skip: list<non-empty-string>,
 *     grammar: array<array-key, \Phplrt\Parser\Grammar\RuleInterface>,
 *     reducers: array<array-key, callable(\Phplrt\Parser\Context, mixed):mixed>,
 *     transitions?: array<array-key, mixed>
 * }
 */
return [
    'initial' => 0,
    'tokens' => [
        'default' => [
            'T_FLOAT' => '\\d+\\.\\d+',
            'T_INT' => '\\d+',
            'T_PLUS' => '\\+',
            'T_MINUS' => '\\-',
            'T_MUL' => '\\*',
            'T_DIV' => '[/÷]',
            'T_BRACE_OPEN' => '\\(',
            'T_BRACE_CLOSE' => '\\)',
            'T_WHITESPACE' => '\\s+',
        ],
    ],
    'skip' => [
        'T_WHITESPACE',
    ],
    'transitions' => [],
    'grammar' => [
        new \Phplrt\Parser\Grammar\Concatenation([1]),
        new \Phplrt\Parser\Grammar\Concatenation([2]),
        new \Phplrt\Parser\Grammar\Concatenation([8, 3]),
        new \Phplrt\Parser\Grammar\Concatenation([14, 9]),
        new \Phplrt\Parser\Grammar\Lexeme('T_PLUS', true),
        new \Phplrt\Parser\Grammar\Lexeme('T_MINUS', true),
        new \Phplrt\Parser\Grammar\Alternation([4, 5]),
        new \Phplrt\Parser\Grammar\Concatenation([3, 6]),
        new \Phplrt\Parser\Grammar\Repetition(7, 0, INF),
        new \Phplrt\Parser\Grammar\Alternation([17, 18]),
        new \Phplrt\Parser\Grammar\Lexeme('T_DIV', true),
        new \Phplrt\Parser\Grammar\Lexeme('T_MUL', true),
        new \Phplrt\Parser\Grammar\Alternation([10, 11]),
        new \Phplrt\Parser\Grammar\Concatenation([9, 12]),
        new \Phplrt\Parser\Grammar\Repetition(13, 0, INF),
        new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        new \Phplrt\Parser\Grammar\Concatenation([15, 0, 16]),
        new \Phplrt\Parser\Grammar\Alternation([19, 20]),
        new \Phplrt\Parser\Grammar\Lexeme('T_FLOAT', true),
        new \Phplrt\Parser\Grammar\Lexeme('T_INT', true),
    ],
    'reducers' => [
        0 => static function (\Phplrt\Parser\Context $ctx, $children) {
            return \is_array($children) ? $children[0] : $children;
        },
        2 => static function (\Phplrt\Parser\Context $ctx, $children) {
            while (\count($children) >= 3) {
                [$a, $op, $b] = [
                    \array_shift($children),
                    \array_shift($children),
                    \array_shift($children),
                ];

                switch ($op->getName()) {
                    case 'T_PLUS':
                        \array_unshift($children, new Ast\Addition([$a, $b], $a->getOffset()));
                        break;

                    case 'T_MINUS':
                        \array_unshift($children, new Ast\Subtraction([$a, $b], $a->getOffset()));
                        break;
                }
            }

            return $children;
        },
        3 => static function (\Phplrt\Parser\Context $ctx, $children) {
            while (\count($children) >= 3) {
                [$a, $op, $b] = [
                    \array_shift($children),
                    \array_shift($children),
                    \array_shift($children),
                ];

                switch ($op->getName()) {
                    case 'T_DIV':
                        \array_unshift($children, new Ast\Division([$a, $b], $a->getOffset()));
                        break;

                    case 'T_MUL':
                        \array_unshift($children, new Ast\Multiplication([$a, $b], $a->getOffset()));
                        break;
                }
            }

            return $children;
        },
        18 => static function (\Phplrt\Parser\Context $ctx, $children) {
            // The "$offset" variable is an auto-generated
            $offset = $ctx->lastProcessedToken->getOffset();

            return new Ast\Literal($children, $offset);
        },
    ],
];