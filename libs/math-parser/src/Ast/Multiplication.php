<?php

/**
 * This file is part of phplrt.org package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\MathParser\Ast;

final class Multiplication extends BinaryExpression
{
    /**
     * @return float|int
     */
    public function eval(): float|int
    {
        return $this->a->eval() * $this->b->eval();
    }
}
