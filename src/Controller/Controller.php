<?php

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
