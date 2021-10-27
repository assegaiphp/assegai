<?php

namespace LifeRaft\Database\Queries;

use LifeRaft\Database\Traits\ExecutableTrait;

final class SQLAssignmentList
{
  use ExecutableTrait;

  public function __construct(
    private SQLQuery $query,
    private array $assignmentList
  )
  {
    $queryString = 'SET ';
    $separator = ', ';
    foreach ($assignmentList as $key => $value)
    {
      if (in_array($key, $this->query->passwordHashFields()))
      {
        $value = password_hash($value, $this->query->passwordHashAlgorithm());
      }
      $queryString .= is_numeric($value)
        ? "`$key`=${value}${separator}"
        : "`$key`='$value'${separator}";
    }
    $queryString = trim($queryString, $separator);
    $this->query->appendQueryString( tail: $queryString );
  }

  public function where(string $condition): SQLWhereClause
  {
    return new SQLWhereClause( query: $this->query, condition: $condition );
  }
}

?>