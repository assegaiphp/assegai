<?php

namespace LifeRaft\Database\Queries;

final class SQLTableReference
{
  public function __construct(
    private SQLQuery $query,
    private array|string $table_references
  )
  {
  }

  public function where(): SQLWhereClause
  {
    return new SQLWhereClause;
  }
}

?>