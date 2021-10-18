<?php
declare(strict_types=1);

use LifeRaft\Database\DBFactory;
use LifeRaft\Database\Queries\SQLColumnDefinition as Column;
use LifeRaft\Database\Queries\SQLDataTypes;
use LifeRaft\Database\Queries\SQLPrimaryGeneratedColumn as PrimaryColumn;
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

  public function testCreateAMysqlDatabase(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->create()->database(dbName: SqlQueryTest::TEST_DB_NAME . '_unit_test' )->execute();
    $this->assertTrue( condition: $result->isOK() );
  }

  // public function testRenameAMysqlDatabase(): void
  // {
  //   $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  //   $result = $query->rename()->database( from: SqlQueryTest::TEST_DB_NAME . '_unit_test', to: SqlQueryTest::TEST_DB_NAME . '_unit_test_renamed' )->execute();
  //   $this->assertTrue( condition: $result->isOK() );
  // }

  public function testSelectADatabase(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  }

  public function testDropDatabase(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->drop()->database(dbName: SqlQueryTest::TEST_DB_NAME . '_unit_test')->execute();
    $this->assertTrue( condition: $result->isOK() );
  }

  public function testCreateATable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->create()->table(
      tableName: 'unit_test_table',
    )->columns(columns: [
      new PrimaryColumn(),
      new Column(name: 'email', dataType: SQLDataTypes::VARCHAR, dataTypeSize: 60, isUnique: true, allowNull: false),
      new Column(name: 'password', dataType: SQLDataTypes::TEXT)
    ])->execute();
    $this->assertTrue($result->isOK());
  }
  
  public function testRenameATable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->rename()->table(from: 'unit_test_table', to: 'unit_test_table_renamed')->execute();
    $this->assertTrue($result->isOK());
  }

  public function testDropTable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->drop()->table( tableName: 'unit_test_table_renamed' )->execute();
    $this->assertTrue($result->isOK());
  }

  public function testInsertASingleRowIntoATable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  }

  public function testInsertMultipleRowsIntoATable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  }

  public function testSelectAllRowsInATable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  }

  public function testSelectARowById(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  }

  public function testSelectARowByPredicate(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  }

  public function testLimitAndOffsetSelectedRows(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  }

  public function testOrderSelectedRows(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  }

  public function testUpdateARowWithGivenId(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  }

  public function testUpdateMultipleRows(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  }

  public function testDeleteASingleRowFromTheTable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  }

  public function testDeleteMultipleRowsFromTheTable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  }
}

?>