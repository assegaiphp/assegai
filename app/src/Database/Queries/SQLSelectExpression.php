<?php

namespace LifeRaft\Database\Queries;

use LifeRaft\Database\Queries\SQLQuery;
use LifeRaft\Database\Queries\SQLTableReference;

final class SQLSelectExpression
{
  public function __construct(
    private SQLQuery $query
  ) { }

  public function from(array|string $table_references): SQLTableReference
  {
    return new SQLTableReference( query: $this->query, table_references: $table_references );
  }
}

?>