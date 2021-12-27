<?php

namespace Assegai\Database\Attributes\Relations;

final class JoinTableOptions
{
  /**
   * @param null|string $name
   * Name of the table that will be created to store values of the both tables (join table).
   * By default is auto generated.
   * @param null|JoinColumnOptions $joinColumn First column of the join table.
   * @param null|JoinColumnOptions $inverseJoinColumn Second (inverse) column of the join table.
   * @param null|string $database Database where join table will be created.
   * Works only in some databases (like mysql and mssql).
   * @param null|string $schema 
   * Schema where join table will be created.
   * Works only in some databases (like postgres and mssql).
   */
  public function __construct(
    public ?string $name = null,
    public ?JoinColumnOptions $joinColumn = null,
    public ?JoinColumnOptions $inverseJoinColumn = null,
    public ?string $database = null,
    public ?string $schema = null,
  ) { }
}