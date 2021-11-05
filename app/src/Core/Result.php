<?php

namespace LifeRaft\Core;

use \ArrayObject;

class Result extends ArrayObject
{
  public function __construct(
    protected array $data = [],
    protected bool $isOK = true
  )
  {
    parent::__construct( array: $data );
  }

  public function isOK(): bool
  {
    return $this->isOK === true;
  }

  public function isError(): bool
  {
    return $this->isOK === false;
  }

  public function value(): array
  {
    return $this->data;
  }
}

?>