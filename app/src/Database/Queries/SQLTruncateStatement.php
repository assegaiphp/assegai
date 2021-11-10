<?php

namespace Assegai\Database\Queries;

use Assegai\Database\Traits\ExecutableTrait;

final class SQLTruncateStatement
{
  use ExecutableTrait;

  public function __construct(
    private SQLQuery $query,
    private string $tableName
  )
  {
    $this->query->setQueryString(queryString: "TRUNCATE TABLE `$tableName`");
  }
}

?>