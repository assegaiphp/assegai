<?php

namespace LifeRaft\Database\Queries;

final class SQLDropDatabaseStatement
{
  public function __construct(
    private SQLQuery $query,
    private string $dbName,
    private bool $checkIfExists = false
  )
  {
    $sql = "DROP DATABASE ";
    if ($checkIfExists)
    {
      $sql .= "IF EXISTS ";
    }
    $sql .= "`$dbName`";

    $this->query->setSQL(sql: $sql);
  }

  public function execute(): SQLQueryResult
  {

    return $this->query->execute();
  }
}

?>