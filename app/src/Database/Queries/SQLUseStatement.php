<?php

namespace LifeRaft\Database\Queries;

use LifeRaft\Database\Traits\ExecutableTrait;

final class SQLUseStatement
{
  use ExecutableTrait;

  public function __construct( private SQLQuery $query, private string $dbName )
  {
    $this->query->appendSQL("USE $this->dbName");
  }
}

?>