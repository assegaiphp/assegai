<?php

namespace Assegai\Database\Queries;

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
      $queryString .= "LOW_PRIORITY ";
    }
    if ($ignore)
    {
      $queryString .= "IGNORE ";
    }
    $queryString = trim($queryString);
    $this->query->setQueryString(queryString: "$queryString `$tableName`");
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