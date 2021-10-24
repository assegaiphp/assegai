<?php

namespace LifeRaft\Database\Attributes;

use Attribute;

#[Attribute]
class Entity
{
  /**
   * @param null|string $tableName Table name. If not specified then naming strategy will generate table name from entity name.
   * @param null|string $orderBy Specifies a default order by used for queries from this table when no explicit order by is specified.
   * @param null|string $engine Table's database engine type (like "InnoDB", "MyISAM", etc).
   * It is used only during table creation.
   * If you update this value and table is already created, it will not change table's engine type.
   * Note that not all databases support this option.
   * @param null|string $database The Database name. Used in Mysql and Sql Server.
   * @param null|string $schema The Schema name. Used in Postgres and Sql Server.
   * @param null|bool $synchronize Indicates if schema synchronization is enabled or disabled for this entity.
   * If it will be set to false then schema sync will and migrations ignore this entity.
   * By default schema synchronization is enabled for all entities.
   * @param null|bool $withRowId If set to 'true' this option disables Sqlite's default behaviour of secretly creating
   * an integer primary key column named 'rowid' on table creation. 
   * @see https://www.sqlite.org/withoutrowid.html.
   */
  public function __construct(
    public ?string $tableName = null,
    public ?string $orderBy = null,
    public ?string $engine = null,
    public ?string $database = null,
    public ?string $schema = null,
    public ?bool $synchronize = true,
    public ?bool $withRowId = false,
  ) { }
}

?>