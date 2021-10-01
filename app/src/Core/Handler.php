<?php

namespace LifeRaft\Core;

use LifeRaft\Core\Attributes\Get;

class Handler
{
    public function __construct(
        protected \ReflectionMethod $method,
        protected Get $attribute
    )
    {
    }

    public function method(): \ReflectionMethod
    {
        return $this->method;
    }

    public function attribute(): Get
    {
        return $this->attribute;
    }
}

?>