<?php

namespace LifeRaft\Database\Queries;

use LifeRaft\Database\Traits\ExecutableTrait;

final class SQLAlterTableOption
{
  use ExecutableTrait;

  public function __construct(
    private SQLQuery $query
  )
  {
  }

  public function addColumn(SQLColumnDefinition $dataType): SQLAlterTableOption
  {
    $this->query->appendQueryString(tail: "ADD $dataType");
    return $this;
  }

  public function modifyColumn(SQLColumnDefinition $dataType): SQLAlterTableOption
  {
    $this->query->appendQueryString(tail: "MODIFY COLUMN $dataType");
    return $this;
  }

  public function renameColumn(string $oldColumnName, string $newColumnName): SQLAlterTableOption
  {
    $this->query->appendQueryString(tail: "RENAME COLUMN `$oldColumnName` TO `$newColumnName`");
    return $this;
  }

  public function dropColumn(string $columnName): SQLAlterTableOption
  {
    $this->query->appendQueryString(tail: "DROP COLUMN `$columnName`");
    return $this;
  }
}

?>