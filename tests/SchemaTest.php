<?php

use Assegai\Database\Schema;
use Assegai\Database\SchemaOptions;
use Assegai\Modules\Users\UserEntity;
use PHPUnit\Framework\TestCase;

final class SchemaTest extends TestCase
{
  public function testCreateATable(): void
  {
    $this->assertTrue(Schema::create(entityClass: UserEntity::class, options: new SchemaOptions(dbName: 'assegai_test')));
  }

  public function testDropATable(): void
  {
    $this->assertTrue(Schema::drop(entityClass: UserEntity::class, options: new SchemaOptions(dbName: 'assegai_test')));
  }

  public function testCheckIfATableExistsBeforeCreatingIt(): void
  {
    // $this->assertTrue(Schema::create(entityClass: ProfileEntity::class, options: new SchemaOptions(dbName: 'assegai_test')));
  }

  public function testRenameATable(): void {}

  public function testUpdateATable(): void {}

  public function testGetInformationAboutATable(): void {}

  public function testEmptyOrTruncateATable(): void {}

  public function testCheckIfATableExistsBeforeDroppingIt(): void {}
}

?>