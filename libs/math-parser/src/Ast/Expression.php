<?php

declare(strict_types=1);

namespace Local\MathParser\Ast;

interface Expression
{
    /**
     * @return int|float
     */
    public function eval(): float|int;
}
