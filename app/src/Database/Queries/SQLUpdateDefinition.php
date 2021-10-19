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
    $queryString = "UPDATE ";
    if ($lowPriority)
    {
      $sql .= "LOW_PRIORITY ";
    }
    if ($ignore)
    {
      $sql .= "IGNORE ";
    }
    $queryString = trim($sql);
    $this->query->setQueryString(sql: "$sql `$tableName`");
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