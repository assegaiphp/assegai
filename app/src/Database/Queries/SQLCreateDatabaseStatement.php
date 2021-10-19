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
    $this->queryString = "CREATE DATABASE ";
    if ($checkIfNotExists)
    {
      $this->queryString .= "IF NOT EXISTS ";
    }
    $this->queryString .= "$dbName ";
    if (!empty($defaultCharacterSet))
    {
      $this->queryString .= "CHARACTER SET $defaultCharacterSet ";
    }
    
    if (!empty($defaultCollation))
    {
      $this->queryString .= "COLLATE $defaultCollation ";
    }
    
    if ($defaultEncryption)
    {
      $this->queryString .= "ENCRYPTION 'Y' ";
    }

    $this->queryString = trim($this->queryString);
    $this->query->setQueryString(queryString: $this->queryString);
  }

  public function execute(): SQLQueryResult
  {
    return $this->query->execute();
  }
}

?>