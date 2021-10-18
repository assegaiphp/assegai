<?php

namespace LifeRaft\Database\Queries;

/**
 * Inserts new rows into an existing table.
 */
final class SQLInsertIntoStatement
{
  private string $sql = '';
  /**
   * @param SQLQuery $query The SQLQuery object
   */
  public function __construct(
    private SQLQuery $query,
    private string $tableName,
    private array $columns = []
  )
  {
    $this->sql = "INSERT INTO `$tableName` ";
    if (!empty($columns))
    {
      $this->sql .= "(" . implode(', ') . ") ";
    }
  }

  public function query(): SQLQuery
  {
    return $this->query;
  }

  public function values(array $values_list): SQLInsertIntoStatement
  {
    $this->sql .= "(" . implode(', ', $values_list) . ") ";
    return $this;
  }

  public function onDuplicateKeyUpdate(array $assignment_list): SQLInsertIntoStatement
  {
    if (!empty($assignment_list))
    {
      $this->sql .= "ON DUPLICATE KEY UPDATE ";
      foreach ($assignment_list as $assignment)
      {
        $this->sql .= "$assignment ";
      }
    }
    $this->sql = trim($this->sql);

    return $this;
  }

  public function execute(): SQLQueryResult
  {
    $this->query->setSQL($this->sql);
    return $this->query->execute();
  }
}

?>