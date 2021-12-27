<?php

namespace Assegai\Database\Queries;

use Assegai\Database\Traits\ExecutableTrait;

final class SQLDescribeStatement
{
  use ExecutableTrait;

  public function __construct( private SQLQuery $query, private string $subject )
  {
    $this->query->appendQueryString("DESCRIBE $this->subject");
  }
}

