<?php

namespace Assegai\Database\Queries;

use Assegai\Database\Traits\ExecutableTrait;

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
      $queryString .= $column . ", ";
    }
    $queryString = trim(string: $queryString, characters: ", ") . ")";
    $this->query->appendQueryString($queryString);
  }
}

?>