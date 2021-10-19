<?php

namespace LifeRaft\Database\Queries;

final class SQLRenameDatabaseStatement
{
  private string $queryString = '';

  public function __construct(
    private SQLQuery $query,
    private string $oldDbName,
    private string $newDbName,
  )
  {
    $this->sql = "CREATE DATABASE `$newDbName` / DROP DATABASE `$oldDbName`";
    $this->query->setQueryString($this->sql);
  }

  public function execute(): SQLQueryResult
  {
    return $this->query->execute();
  }
}

?>