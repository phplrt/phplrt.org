<?php

/**
 * This file is part of phplrt.org package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\MathParser\Ast;

use Phplrt\Contracts\Ast\NodeInterface;

abstract class TreeNode implements NodeInterface
{
    /**
     * @param positive-int|0 $offset
     */
    public function __construct(private int $offset)
    {
    }

    /**
     * @return positive-int|0
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \EmptyIterator();
    }
}
