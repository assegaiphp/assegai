<?php

namespace Assegai\Core\Exceptions\Container;

class ResolveException extends ContainerException
{
  public function __construct(string $id, string $message)
  {
    parent::__construct(message: "Resolve Error ($id): $message");
  }
}