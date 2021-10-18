<?php

namespace LifeRaft\Database\Queries;

final class SQLTableOptions
{
  public function __construct(
    private SQLQuery $query,
    private array $columns,
    private string $comment = ""
  )
  {
    $primaryKeyAlreadySet = false;
    $sql = "(";
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
    $sql = trim(string: $sql, characters: ", ") . ")";
    $this->query->appendSQL($sql);
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

?>