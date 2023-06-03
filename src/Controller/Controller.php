<?php

/**
 * This file is part of phplrt.org package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller;

use Twig\Environment;

abstract class Controller
{
    /**
     * @var Environment
     */
    protected Environment $view;

    /**
     * @param Environment $view
     */
    public function __construct(Environment $view)
    {
        $this->view = $view;
    }
}
