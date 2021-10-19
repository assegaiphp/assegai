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
    $queryString = "DROP DATABASE ";
    if ($checkIfExists)
    {
      $queryString .= "IF EXISTS ";
    }
    $queryString .= "`$dbName`";

    $this->query->setQueryString(queryString: $queryString);
  }

  public function execute(): SQLQueryResult
  {

    return $this->query->execute();
  }
}

?>