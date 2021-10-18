<?php

namespace LifeRaft\Database\Queries;

final class SQLPrimaryGeneratedColumn extends SQLColumnDefinition
{
  public function __construct(
    private string $name = 'id',
    private string $comment = ""
  )
  {
    parent::__construct(
      name: $name,
      dataType: SQLDataTypes::BIGINT_UNSIGNED,
      allowNull: false,
      autoIncrement: true,
      isPrimaryKey: true,
      comment: $comment,
    );
  }
}