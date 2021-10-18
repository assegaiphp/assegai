<?php

namespace LifeRaft\Database\Queries;

final class SQLCreateDefinition
{
  public function __construct(
    protected SQLQuery $query
  ) {}

  public function table(
    string $tableName,
    array $columns,
    bool $isTemporary = false,
    bool $checkIfNotExists = true
  ): SQLTableOptions
  {
    $sql = "CREATE ";
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
    $this->query->setSQL($sql);
    return new SQLTableOptions( query: $this->query, columns: $columns );
  }
  
  public function database(string $dbName): mixed
  {
    $this->query->setSQL("CREATE DATABASE $dbName");
    return null;
  }

  public function execute(): SQLQueryResult
  {
    return $this->query->execute();
  }
}

?>