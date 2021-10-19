<?php

namespace LifeRaft\Database\Queries;

final class SQLCreateDatabaseStatement
{
  private string $queryString = '';

  public function __construct(
    private SQLQuery $query,
    private string $dbName,
    private string $defaultCharacterSet = 'utf8mb4',
    private string $defaultCollation = 'utf8mb4_general_ci',
    private bool $defaultEncryption = true,
    private bool $checkIfNotExists = true
  )
  {
    $this->sql = "CREATE DATABASE ";
    if ($checkIfNotExists)
    {
      $this->sql .= "IF NOT EXISTS ";
    }
    $this->sql .= "$dbName ";
    if (!empty($defaultCharacterSet))
    {
      $this->sql .= "CHARACTER SET $defaultCharacterSet ";
    }
    
    if (!empty($defaultCollation))
    {
      $this->sql .= "COLLATE $defaultCollation ";
    }
    
    if ($defaultEncryption)
    {
      $this->sql .= "ENCRYPTION 'Y' ";
    }

    $this->sql = trim($this->sql);
    $this->query->setQueryString(sql: $this->sql);
  }

  public function execute(): SQLQueryResult
  {
    return $this->query->execute();
  }
}

?>