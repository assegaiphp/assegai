<?php

namespace LifeRaft\Database\Queries;

final class SQLDescribeStatement
{
  public function __construct( private SQLQuery $query, private string $subject )
  {
    $this->query->appendSQL("DESCRIBE $this->subject");
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

