<?php

namespace LifeRaft\Database\Queries;

final class SQLUpdateDefinition
{
  public function __construct(
    private SQLQuery $query,
    private string $tableName,
    private bool $lowPriority = false,
    private bool $ignore =  false
  )
  {
    $sql = "UPDATE ";
    if ($lowPriority)
    {
      $sql .= "LOW_PRIORITY ";
    }
    if ($ignore)
    {
      $sql .= "IGNORE ";
    }
    $sql = trim($sql);
    $this->query->setSQL(sql: "$sql `$tableName`");
  }

  public function set(array $assignmentList): SQLAssignmentList
  {
    return new SQLAssignmentList(
      query: $this->query,
      assignmentList: $assignmentList
    );
  }
}

?>