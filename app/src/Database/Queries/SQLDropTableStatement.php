<?php

namespace LifeRaft\Database\Queries;

use LifeRaft\Database\Traits\ExecutableTrait;

/**
 * Removes one or more tables. You must have the [DROP](https://dev.mysql.com/doc/refman/8.0/en/privileges-provided.html#priv_drop) privelege for each table.
 */
final class SQLDropTableStatement
{
  use ExecutableTrait;

  public function __construct(
    private SQLQuery $query,
    private string $tableName,
    private bool $checkIfExists = true
  )
  {
    $queryString = "DROP TABLE ";
    if ($checkIfExists)
    {
      $sql .= "IF EXISTS ";
    }
    $sql .= "`$tableName`";
    $this->query->setQueryString(queryString: $queryString);
  }
}

?>