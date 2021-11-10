<?php

namespace Assegai\Database\Queries;

use Assegai\Database\Queries\SQLQuery;
use Assegai\Database\Queries\SQLTableReference;

final class SQLSelectExpression
{
  public function __construct(
    private SQLQuery $query
  ) { }

  public function from(array|string $tableReferences): SQLTableReference
  {
    return new SQLTableReference( query: $this->query, tableReferences: $tableReferences );
  }
}

?>