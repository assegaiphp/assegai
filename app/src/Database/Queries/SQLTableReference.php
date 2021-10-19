<?php

namespace LifeRaft\Database\Queries;

use LifeRaft\Database\Traits\ExecutableTrait;
use LifeRaft\Database\Traits\SQLAggregatorTrait;

final class SQLTableReference
{
  use ExecutableTrait;
  use SQLAggregatorTrait;

  public function __construct(
    private SQLQuery $query,
    private array|string $table_references
  ) {
    $sql = "FROM ";
    $separate = ', ';

    if (is_string($table_references))
    {
      $sql .= "`$table_references`";
    }
    else
    {
      foreach ($table_references as $alias => $reference)
      {
        if (is_numeric($alias))
        {
          # We don't have an alias
          $sql .= "`${reference}`${separate}";
        }
        else
        {
          $sql .= "`${reference}` AS ${alias}${separate}";
        }
      }
      $sql = trim($sql, $separate);
    }
    $this->query->appendSQL(tail: $sql);
  }

  public function where(string $condition): SQLWhereClause
  {
    return new SQLWhereClause(
      query: $this->query,
      condition: $condition
    );
  }

  public function having(string $condition): SQLHavingClause
  {
    return new SQLHavingClause(
      query: $this->query,
      condition: $condition
    );
  }
}

?>