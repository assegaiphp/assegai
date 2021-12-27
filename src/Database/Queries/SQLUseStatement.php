<?php

namespace Assegai\Database\Queries;

use Assegai\Database\Traits\ExecutableTrait;

final class SQLUseStatement
{
  use ExecutableTrait;

  public function __construct( private SQLQuery $query, private string $dbName )
  {
    $this->query->appendQueryString("USE $this->dbName");
  }
}

?>