<?php

use Assegai\Database\Attributes\Columns\Column;
use Assegai\Database\Attributes\Entity;
use Assegai\Database\BaseEntity;
use Assegai\Database\Interfaces\IEntity;
use Assegai\Database\Queries\SQLDataTypes;
use PHPUnit\Framework\TestCase;

defined('DATABASE_NAME') or define('DATABASE_NAME', 'assegai_test');
defined('TABLE_NAME') or define('TABLE_NAME', 'tests');
defined('JOIN_TABLE_NAME') or define('JOIN_TABLE_NAME', 'tests');

#[Entity(tableName: TABLE_NAME, database: DATABASE_NAME)]
class MockEntity extends BaseEntity implements IEntity
{
  #[Column(dataType: SQLDataTypes::VARCHAR, lengthOrValues: 100)]
  public string $name = '';

  #[Column(dataType: SQLDataTypes::TEXT, allowNull: true, defaultValue: null)]
  public string $description = '';

  #[Column(dataType: SQLDataTypes::VARCHAR, lengthOrValues: 100, allowNull: true, defaultValue: null)]
  public string $value = '';
}



final class RelationsTest extends TestCase
{
  public function testShouldCreateAOneToOneRelationship(): void
  {
  }

  public function testShouldCreateAOneToOneRelationshipWithEagerLoading(): void
  {
  }

  public function testShouldCreateAOneToOneRelationshipThatCascades(): void
  {
  }

  public function testShouldCreateAManyToOneRelationship(): void
  {
  }

  public function testShouldCreateAManyToOneRelationshipWithEagerLoading(): void
  {
  }

  public function testShouldCreateAManyToOneRelationshipThatCascades(): void
  {
  }

  public function testShouldCreateAOneToManyRelationship(): void
  {
  }

  public function testShouldCreateAOneToManyRelationshipWithEagerLoading(): void
  {
  }

  public function testShouldCreateAOneToManyRelationshipThatCascades(): void
  {
  }

  public function testShouldCreateAManyToManyRelationship(): void
  {
  }

  public function testShouldCreateAManyToManyRelationshipWithEagerLoading(): void
  {
  }

  public function testShouldCreateAManyToManyRelationshipThatCascades(): void
  {
  }

}

?>