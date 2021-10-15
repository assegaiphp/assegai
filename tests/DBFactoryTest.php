<?php
declare(strict_types=1);

use LifeRaft\Database\DBFactory;
use PHPUnit\Framework\TestCase;

final class DBFactoryTest extends TestCase
{
  public function testGetMysqlConnection(): void
  {
    $this->assertInstanceOf(PDO::class, DBFactory::getMySQLConnection('assegai_test'));
  }

  public function testGetMariadbConnection(): void
  {
    $this->assertInstanceOf(PDO::class, DBFactory::getMariaDBConnection('assegai_test'));
  }
  
  public function testGetPostgresqlConnection(): void
  {
    $this->assertInstanceOf(PDO::class, DBFactory::getPostgreSQLConnection('assegai_test'));
  }

  public function testGetSqliteConnection(): void
  {
    $this->assertInstanceOf(PDO::class, DBFactory::getSQLiteConnection('assegai_test'));
  }
}

?>