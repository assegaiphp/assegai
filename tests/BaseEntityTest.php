<?php
declare(strict_types=1);

use LifeRaft\Database\BaseEntity;
use PHPUnit\Framework\TestCase;

final class BaseEntityTest extends TestCase
{
  public function testCreateInstance(): void
  {
    $this->assertInstanceOf(BaseEntity::class, new BaseEntity);
  }

  public function testCreateInstanceFromStdclassObject(): void
  {
    $object = json_decode(json_encode([
      'id' => 1,
      'createdAt' => date(DATE_ATOM),
      'updatedAt' => date(DATE_ATOM),
      'deletedAt' => '1000-01-01T00:00:00Z',
    ]));
    $instance = BaseEntity::newInstanceFromObject(object: $object);
    $this->assertInstanceOf(BaseEntity::class, $instance);
    $this->assertEquals($object->id, $instance->id);
    $this->assertEquals($object->createdAt, $instance->createdAt);
    $this->assertEquals($object->updatedAt, $instance->updatedAt);
    $this->assertEquals($object->deletedAt, $instance->deletedAt);
  }

  public function testCreateInstanceFromAssociativeArray(): void
  {
    $instance = BaseEntity::newInstanceFromArray(array: [
      'id' => 1,
      'createdAt' => date(DATE_ATOM),
      'updatedAt' => date(DATE_ATOM),
      'deletedAt' => '1000-01-01T00:00:00Z',
    ]);
    $this->assertInstanceOf(BaseEntity::class, $instance);
  }

  public function testGetEntityColumns(): void
  {
    $entity = new BaseEntity;
    $columns = [
      'id',
      'createdAt' => 'created_at',
      'updatedAt' => 'updated_at',
      'deletedAt' => 'deleted_at'
    ];
    $this->assertIsArray($entity::columns());
    $this->assertEquals(expected: $columns, actual: $entity::columns());
  }

  public function testGetEntityColumnValues(): void
  {
    $entity = BaseEntity::newInstanceFromArray([
      'id' => 1,
      'createdAt' => date(DATE_ATOM),
      'updatedAt' => date(DATE_ATOM),
      'deletedAt' => '1000-01-01 00:00:00',
    ]);
    $values = [1, date(DATE_ATOM), date(DATE_ATOM), '1000-01-01 00:00:00'];
    $this->assertIsArray($entity->values());
    $this->assertEquals(expected: $values, actual: $entity->values());
  }
}

?>