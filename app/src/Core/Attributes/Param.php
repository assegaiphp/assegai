<?php

namespace LifeRaft\Core\Attributes;

use Attribute;

#[Attribute( flags: Attribute::TARGET_PARAMETER)]
class Param
{

    public function __construct(
        public ?string $name
    )
    {   
    }
}

?>