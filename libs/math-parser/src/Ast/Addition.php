<?php

declare(strict_types=1);

namespace Local\MathParser\Ast;

final class Addition extends BinaryExpression
{
    /**
     * @return float|int
     */
    public function eval(): float|int
    {
        return $this->a->eval() + $this->b->eval();
    }
}
