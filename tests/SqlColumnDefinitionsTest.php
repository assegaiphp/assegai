<?php
declare(strict_types=1);

use Assegai\Database\Queries\SQLColumnDefinition as Column;
use Assegai\Database\Queries\SQLDataTypes;
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
    $queryString = "`id` BIGINT UNSIGNED NULL";
    $this->assertEquals($queryString, strval($column));
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