<?php

namespace LifeRaft\Database\Queries;

final class SQLRenameTableStatement
{
  private string $queryString = '';

  public function __construct(
    private SQLQuery $query,
    private string $oldTableName,
    private string $newTableName,
  )
  {
    $this->sql = "RENAME TABLE `$oldTableName` TO `$newTableName`";
    $this->query->setQueryString($this->sql);
  }

  public function execute(): SQLQueryResult
  {
    return $this->query->execute();
  }
}

?>