<?php

namespace LifeRaft\Database\Queries;

use LifeRaft\Database\Traits\DuplicateKeyUpdateableTrait;
use LifeRaft\Database\Traits\ExecutableTrait;

/**
 * Inserts new rows into an existing table.
 */
final class SQLInsertIntoStatement
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
    private array $columns = []
  )
  {
    $queryString = "";

    if (!empty($columns))
    {
      $queryString = "(" . implode(', ', $columns) . ") ";
      $this->hashableIndexes = array_keys( array_intersect( $columns, $this->query->passwordHashFields() ) );
    }

    $this->query->appendQueryString($queryString);
  }

  public function values(array $valuesList): SQLInsertIntoStatement
  {
    $queryString = "VALUES(";
    $separator = ', ';
    $quoteExemptions = ['CURRENT_TIMESTAMP'];

    foreach ($valuesList as $index => $value)
    {
      if (in_array($index, $this->hashableIndexes))
      {
        $value = password_hash($value, $this->query->passwordHashAlgorithm());
      }
      if (is_bool($value))
      {
        $value = (int)$value;
      }
      $queryString .= is_numeric($value) || in_array($value, $quoteExemptions) ? "${value}${separator}" : "'${value}'${separator}";
    }

    $queryString = trim(string: $queryString, characters: $separator) . ") ";
    $this->query->appendQueryString($queryString);
    return $this;
  }

}

?>