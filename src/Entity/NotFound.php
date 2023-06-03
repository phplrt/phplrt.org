<?php

/**
 * This file is part of phplrt.org package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

class NotFound extends Documentation
{
    public function __construct()
    {
        parent::__construct('Not Found', '', '
            <h1 style="text-align: center">Page Not Found</h1>
        ');
    }
}
