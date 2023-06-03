<?php

use App\MathParser\Ast;

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
    'transitions' => [
        
    ],
    'grammar' => [
        0 => new \Phplrt\Parser\Grammar\Alternation(['Addition', 'Subtraction', 1]),
        'Addition' => new \Phplrt\Parser\Grammar\Concatenation([1, 7, 0]),
        'Division' => new \Phplrt\Parser\Grammar\Concatenation([2, 11, 1]),
        'Multiplication' => new \Phplrt\Parser\Grammar\Alternation([9, 10]),
        'Subtraction' => new \Phplrt\Parser\Grammar\Concatenation([1, 6, 0]),
        1 => new \Phplrt\Parser\Grammar\Alternation(['Multiplication', 'Division', 2]),
        2 => new \Phplrt\Parser\Grammar\Alternation([5, 'Value']),
        3 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        4 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        5 => new \Phplrt\Parser\Grammar\Concatenation([3, 0, 4]),
        6 => new \Phplrt\Parser\Grammar\Lexeme('T_MINUS', false),
        7 => new \Phplrt\Parser\Grammar\Lexeme('T_PLUS', false),
        8 => new \Phplrt\Parser\Grammar\Lexeme('T_MUL', false),
        9 => new \Phplrt\Parser\Grammar\Concatenation([2, 8, 1]),
        10 => new \Phplrt\Parser\Grammar\Concatenation([2, 1]),
        11 => new \Phplrt\Parser\Grammar\Lexeme('T_DIV', false),
        12 => new \Phplrt\Parser\Grammar\Lexeme('T_FLOAT', true),
        13 => new \Phplrt\Parser\Grammar\Lexeme('T_INT', true),
        'Value' => new \Phplrt\Parser\Grammar\Alternation([12, 13])
    ],
    'reducers' => [
        2 => function (\Phplrt\Parser\Context $ctx, $children) {
            if (\is_array($children)) {
            return $children[0];
        }
        return $children;
        },
        'Subtraction' => function (\Phplrt\Parser\Context $ctx, $children) {
            $token = $ctx->getToken();
            return new Ast\Subtraction($children, $token->getOffset());
        },
        'Addition' => function (\Phplrt\Parser\Context $ctx, $children) {
            $token = $ctx->getToken();
            return new Ast\Addition($children, $token->getOffset());
        },
        'Multiplication' => function (\Phplrt\Parser\Context $ctx, $children) {
            $token = $ctx->getToken();
            return new Ast\Multiplication($children, $token->getOffset());
        },
        'Division' => function (\Phplrt\Parser\Context $ctx, $children) {
            $token = $ctx->getToken();
            return new Ast\Division($children, $token->getOffset());
        },
        'Value' => function (\Phplrt\Parser\Context $ctx, $children) {
            $token = $ctx->getToken();
            return new Ast\Value($children, $token->getOffset());
        }
    ]
];