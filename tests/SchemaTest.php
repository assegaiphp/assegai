<?php

use LifeRaft\Database\Schema;
use LifeRaft\Database\SchemaOptions;
use LifeRaft\Modules\Profile\ProfileEntity;
use LifeRaft\Modules\Tests\TestEntity;
use LifeRaft\Modules\Users\UserEntity;
use PHPUnit\Framework\TestCase;

final class SchemaTest extends TestCase
{
  public function testCreateATable(): void {
    $this->assertTrue(Schema::create(entityClass: UserEntity::class, options: new SchemaOptions(dbName: 'assegai_test')));
  }
  public function testCheckIfATableExistsBeforeCreatingIt(): void {
    // $this->assertTrue(Schema::create(entityClass: ProfileEntity::class, options: new SchemaOptions(dbName: 'assegai_test')));
  }
  public function testRenameATable(): void {}
  public function testUpdateATable(): void {}
  public function testGetInformationAboutATable(): void {}
  public function testEmptyOrTruncateATable(): void {}
  public function testDropATable(): void {}
  public function testCheckIfATableExistsBeforeDroppingIt(): void {}
}

?>