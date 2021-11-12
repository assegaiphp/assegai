<?php

namespace Assegai\Core;

use Assegai\Core\Attributes\Delete;
use Assegai\Core\Attributes\Get;
use Assegai\Core\Attributes\Post;
use Assegai\Core\Attributes\Options;
use Assegai\Core\Attributes\Patch;
use Assegai\Core\Attributes\Put;
use ReflectionMethod;

class Handler
{
    public function __construct(
        protected ReflectionMethod $method,
        protected Delete|Get|Options|Patch|Post|Put $attribute
    ) { }

    public function method(): ReflectionMethod
    {
        return $this->method;
    }

    public function attribute(): Delete|Get|Options|Patch|Post|Put
    {
        return $this->attribute;
    }
}

