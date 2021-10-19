<?php

namespace LifeRaft\Database\Queries;

use LifeRaft\Database\Traits\ExecutableTrait;

final class SQLDescribeStatement
{
  use ExecutableTrait;

  public function __construct( private SQLQuery $query, private string $subject )
  {
    $this->query->appendSQL("DESCRIBE $this->subject");
  }
}

