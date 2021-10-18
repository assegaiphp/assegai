<?php

namespace LifeRaft\Database\Queries;

final class SQLRenameDatabaseStatement
{
  private string $sql = '';

  public function __construct(
    private SQLQuery $query,
    private string $oldDbName,
    private string $newDbName,
  )
  {
    $this->sql = "CREATE DATABASE `$newDbName` / DROP DATABASE `$oldDbName`";
    $this->query->setSQL($this->sql);
  }

  public function execute(): SQLQueryResult
  {
    return $this->query->execute();
  }
}

?>