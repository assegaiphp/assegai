<?php

namespace LifeRaft\Database\Queries;

final class SQLUseStatement
{
  public function __construct( private SQLQuery $query, private string $dbName )
  {
    $this->query->appendSQL("USE $this->dbName");
  }

  public function query(): SQLQuery
  {
    return $this->query;
  }

  public function execute(): SQLQueryResult
  {
    return $this->query->execute();
  }
}

?>