<?php

namespace LifeRaft\Core;

use LifeRaft\Core\Attributes\Delete;
use LifeRaft\Core\Attributes\Get;
use LifeRaft\Core\Attributes\Post;
use LifeRaft\Core\Attributes\Options;
use LifeRaft\Core\Attributes\Patch;
use LifeRaft\Core\Attributes\Put;

class Handler
{
    public function __construct(
        protected \ReflectionMethod $method,
        protected Delete|Get|Options|Patch|Post|Put $attribute
    )
    {
    }

    public function method(): \ReflectionMethod
    {
        return $this->method;
    }

    public function attribute(): Delete|Get|Options|Patch|Post|Put
    {
        return $this->attribute;
    }
}

?>