<?php
declare(strict_types=1);

use LifeRaft\Database\DBFactory;
use LifeRaft\Database\Queries\SQLQuery;
use PHPUnit\Framework\TestCase;

final class SqlQueryTest extends TestCase
{
  public function testEstablishDbConnection(): void
  {
    $db = DBFactory::getMySQLConnection('assegai_test');
    $this->assertInstanceOf(\PDO::class, $db);
  }

  // public function testCreateInstance(): void
  // {
  //   $query = new SQLQuery;
  //   $query->select()->from(['users' => 'u'])->where();
  //   $this->assertInstanceOf(SQLQuery::class, new SQLQuery);
  // }

}

?>