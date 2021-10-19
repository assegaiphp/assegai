<?php

namespace LifeRaft\Database\Queries;

use LifeRaft\Database\Traits\ExecutableTrait;

final class SQLTableOptions
{
  use ExecutableTrait;

  public function __construct(
    private SQLQuery $query,
    private array $columns,
    private string $comment = ""
  )
  {
    $primaryKeyAlreadySet = false;
    $queryString = "(";
    foreach ($this->columns as $column)
    {
      $column = strval($column);

      if (str_contains($column, 'PRIMARY KEY'))
      {
        if ($primaryKeyAlreadySet)
        {
          $column = str_replace('PRIMARY KEY', '', $column);
        }

        $primaryKeyAlreadySet = true;
      }
      $sql .= $column . ", ";
    }
    $queryString = trim(string: $sql, characters: ", ") . ")";
    $this->query->appendQueryString($sql);
  }
}

?>