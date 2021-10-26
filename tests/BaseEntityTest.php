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
    $this->assertInstanceOf(BaseEntity::class, new BaseEntity);
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
    $this->assertInstanceOf(BaseEntity::class, new BaseEntity);
  }
}

?>