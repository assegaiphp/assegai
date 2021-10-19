<?php

namespace LifeRaft\Database\Queries;

final class SQLCreateTableStatement
{
  public function __construct(
    private SQLQuery $query,
    private string $tableName,
    private bool $isTemporary,
    private bool $checkIfNotExists
  )
  {
    $queryString = "CREATE ";
    if ($isTemporary)
    {
      $sql .= "TEMPORARY ";
    }
    $sql .= "TABLE ";
    if ($checkIfNotExists)
    {
      $sql .= "IF NOT EXISTS ";
    }
    $sql .= "$tableName";
    $this->query->setQueryString(queryString: $queryString);
  }

  public function columns(array $columns): SQLTableOptions
  {
    return new SQLTableOptions( query: $this->query, columns: $columns );
  }
}

?>