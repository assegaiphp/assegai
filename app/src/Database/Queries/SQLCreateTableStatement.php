<?php

namespace Assegai\Database\Queries;

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
      $queryString .= "TEMPORARY ";
    }
    $queryString .= "TABLE ";
    if ($checkIfNotExists)
    {
      $queryString .= "IF NOT EXISTS ";
    }
    $queryString .= "$tableName";
    $this->query->setQueryString(queryString: $queryString);
  }

  public function columns(array $columns): SQLTableOptions
  {
    return new SQLTableOptions( query: $this->query, columns: $columns );
  }
}

?>