<?php

namespace LifeRaft\Core;

class BaseEntity
{
  public function __construct(
    protected array $columns = []
  )
  {
  }
}

?>