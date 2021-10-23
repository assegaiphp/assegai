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
    private array|string $tableReferences
  ) {
    $queryString = "FROM ";
    $separate = ', ';

    if (is_string($tableReferences))
    {
      $queryString .= "`$tableReferences`";
    }
    else
    {
      foreach ($tableReferences as $alias => $reference)
      {
        if (is_numeric($alias))
        {
          # We don't have an alias
          $queryString .= "`${reference}`${separate}";
        }
        else
        {
          $queryString .= "`${reference}` AS ${alias}${separate}";
        }
      }
      $queryString = trim($queryString, $separate);
    }
    $this->query->appendQueryString(tail: $queryString);
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