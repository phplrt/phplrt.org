<?php

namespace App\Domain\Documentation;

use Doctrine\ORM\Mapping as ORM;

/**
 * @final
 */
#[ORM\Entity]
class Link extends Page
{
    public function getType(): string
    {
        return 'link';
    }
}
