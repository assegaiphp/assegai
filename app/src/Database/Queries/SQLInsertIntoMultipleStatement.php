<?php

namespace LifeRaft\Database\Queries;

use LifeRaft\Database\Traits\DuplicateKeyUpdateableTrait;
use LifeRaft\Database\Traits\ExecutableTrait;

final class SQLInsertIntoMultipleStatement
{
  use DuplicateKeyUpdateableTrait;
  use ExecutableTrait;

  private array $hashableIndexes = [];

  /**
   * @param SQLQuery $query The SQLQuery object.
   * @param array $columns A parenthesized list of comma-separated column names for which the statment provides values.
   */
  public function __construct(
    private SQLQuery $query,
    private array $columns
  )
  {
    $sql = "";
    
    if (!empty($columns))
    {
      $sql = "(" . implode(', ', $columns) . ") ";
      $this->hashableIndexes = array_keys( array_intersect( $columns, $this->query->passwordHashFields() ) );
    }

    $this->query->appendSQL($sql);
  }

  public function rows(array $rows_list): SQLInsertIntoMultipleStatement
  {
    $sql = "VALUES ";
    $separator = ', ';

    foreach ($rows_list as $row)
    {
      $sql .= "ROW(";
      foreach ($row as $index => $value)
      {
        if (in_array($index, $this->hashableIndexes))
        {
          $value = password_hash($value, $this->query->passwordHashAlgorithm());
        }
        $sql .= is_numeric($value) ? "${value}${separator}" : "'${value}'${separator}";
      }
      $sql = trim($sql, $separator);
      $sql .= ")$separator";
    }
    $sql = trim($sql, $separator);
    $this->query->appendSQL(tail: $sql);
    return $this;
  }
}

?>