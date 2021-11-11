<?php

namespace Assegai\Database\Queries;

final class SQLRenameDatabaseStatement
{
  private string $queryString = '';

  public function __construct(
    private SQLQuery $query,
    private string $oldDbName,
    private string $newDbName,
  )
  {
    $this->queryString = "CREATE DATABASE `$newDbName` / DROP DATABASE `$oldDbName`";
    $this->query->setQueryString($this->queryString);
  }

  public function execute(): SQLQueryResult
  {
    return $this->query->execute();
  }
}

?>