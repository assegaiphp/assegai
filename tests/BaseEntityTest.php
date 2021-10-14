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
}

?>