<?php
declare(strict_types=1);

use LifeRaft\Database\Queries\SQLColumnDefinition as Column;
use LifeRaft\Database\Queries\SQLDataTypes;
use PHPUnit\Framework\TestCase;

final class SqlColumnDefinitionsTest extends TestCase
{
  public function testCreateInstance(): void
  {
    $column = new Column(
      name: 'id',
      dataType: SQLDataTypes::BIGINT
    );
    $this->assertInstanceOf(Column::class, $column);
  }

  public function testGetSqlEquivalentString(): void
  {
    $column = new Column(
      name: 'id',
      dataType: SQLDataTypes::BIGINT_UNSIGNED
    );
    $sql = "id BIGINT UNSIGNED NULL";
    $this->assertEquals($sql, strval($column));
  }

  public function testConvertToString(): void
  {
    $column = new Column(
      name: 'id',
      dataType: SQLDataTypes::BIGINT_UNSIGNED
    );
    $this->assertIsString(strval($column));
  }
}

?>