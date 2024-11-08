<?php

declare(strict_types=1);

namespace App\Presentation\Request\Attribute;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
final class MapBody extends MapRequestPayloadAttribute {}
