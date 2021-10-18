<?php
declare(strict_types=1);

use LifeRaft\Database\DBFactory;
use LifeRaft\Database\Queries\SQLQuery;
use PHPUnit\Framework\TestCase;

final class SqlQueryTest extends TestCase
{
  const TEST_DB_NAME = 'assegai_test';

  public function testEstablishDbConnection(): void
  {
    $db = DBFactory::getMySQLConnection( dbName: SqlQueryTest::TEST_DB_NAME );
    $this->assertInstanceOf(\PDO::class, $db);
  }

  public function testInstantiateAQuery(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $this->assertInstanceOf(SQLQuery::class, $query);
  }

  public function testSetAndGetTheQueryString(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $str = 'Hello World';
    $query->setSQL($str);
    $this->assertEquals($str, strval($query));
  }

  public function testAppendQueryString(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $str = 'SELECT *';
    $query->setSQL($str);
    $tail = 'FROM `users`';
    $query->appendSQL($tail);
    $this->assertEquals($str . ' ' . $tail, $query->sql());
  }

  public function testCreateAndRenameAMysqlDatabase(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->create()->database(dbName: 'test_db')->execute();
    $this->assertTrue( condition: $result->isOK() );
  }

  public function testSelectADatabase(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  }

  public function testDropDatabase(): void
  {
  }

  public function testCreateAndRenameATable(): void
  {
  }

  public function testDropTable(): void
  {
  }

  public function testInsertASingleRowIntoATable(): void
  {
  }

  public function testSelectAllRowsInATable(): void
  {
  }

  public function testSelectARowById(): void
  {
  }

  public function testSelectARowByPredicate(): void
  {
  }

  public function testLimitAndOffsetSelectedRows(): void
  {
  }

  public function testOrderSelectedRows(): void
  {
  }

  public function testUpdateARowWithGivenId(): void
  {
  }

  public function testUpdateMultipleRows(): void
  {
  }

  public function testDeleteASingleRowFromTheTable(): void
  {
  }

  public function testDeleteMultipleRowsFromTheTable(): void
  {
  }
}

?>