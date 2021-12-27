<?php

namespace Assegai\Database\Queries;

use Assegai\Database\Traits\ExecutableTrait;

final class SQLDeleteFromStatement
{
  use ExecutableTrait;

  public function __construct(
    private SQLQuery $query,
    private string $tableName,
    private ?string $alias = null
  )
  {
    $queryString = "DELETE FROM $tableName";
    if (!is_null($alias))
    {
      $queryString .= "AS $alias";
    }
    $this->query->setQueryString(queryString: $queryString);
  }

  public function where(string $condition): SQLWhereClause
  {
    return new SQLWhereClause( query: $this->query, condition: $condition );
  }
}

?>