<?php
declare(strict_types=1);

use LifeRaft\Database\DBFactory;
use LifeRaft\Database\Queries\SQLColumnDefinition as Column;
use LifeRaft\Database\Queries\SQLDataTypes;
use LifeRaft\Database\Queries\SQLKeyPart;
use LifeRaft\Database\Queries\SQLPrimaryGeneratedColumn as PrimaryColumn;
use LifeRaft\Database\Queries\SQLQuery;
use PHPUnit\Framework\TestCase;

final class SqlQueryTest extends TestCase
{
  const TEST_DB_NAME = 'assegai_test';
  const TEST_TABLE_NAME = 'unit_test';

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
    $query->setQueryString($str);
    $this->assertEquals($str, strval($query));
  }

  public function testAppendQueryString(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $str = 'SELECT *';
    $query->setQueryString($str);
    $tail = 'FROM `users`';
    $query->appendQueryString($tail);
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

  // public function testSelectADatabase(): void
  // {
  //   $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  // }

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
      tableName: SqlQueryTest::TEST_TABLE_NAME,
    )->columns(columns: [
      new PrimaryColumn(),
      new Column(name: 'email', dataType: SQLDataTypes::VARCHAR, dataTypeSize: 60, isUnique: true, allowNull: false),
      new Column(name: 'password', dataType: SQLDataTypes::TEXT)
    ])->execute();
    $this->assertTrue( condition: $result->isOK() );
  }
  
  public function testRenameATable(): void
  {
    $table_name = SqlQueryTest::TEST_TABLE_NAME;
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->rename()->table(from: $table_name, to: $table_name . '_renamed')->execute();
    $this->assertTrue( condition: $result->isOK() );
  }

  public function testAlterATable(): void
  {
  }

  public function testDropTable(): void
  {
    $table_name = SqlQueryTest::TEST_TABLE_NAME;
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->drop()->table( tableName: $table_name . '_renamed' )->execute();
    $this->assertTrue( condition: $result->isOK() );
  }

  public function testInsertASingleRowIntoATable(): void
  {
    $query = new SQLQuery(
      db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ),
      passwordHashAlgorithm: PASSWORD_ARGON2I
    );

    $result = $query->create()->table(
      tableName: SqlQueryTest::TEST_TABLE_NAME,
    )->columns(columns: [
      new PrimaryColumn(),
      new Column(name: 'email', dataType: SQLDataTypes::VARCHAR, dataTypeSize: 60, isUnique: true, allowNull: false),
      new Column(name: 'password', dataType: SQLDataTypes::TEXT),
      new Column(name: 'isVerified', dataType: SQLDataTypes::BOOLEAN, defaultValue: 0, allowNull: false),
      new Column(name: 'created_at', dataType: SQLDataTypes::DATETIME, defaultValue: 'CURRENT_TIMESTAMP'),
      new Column(name: 'updated_at', dataType: SQLDataTypes::DATETIME, defaultValue: 'CURRENT_TIMESTAMP', onUpdate: 'CURRENT_TIMESTAMP'),
    ])->execute();

    if ($result->isOK())
    {
      $query->init();
      $result =
        $query->insertInto(tableName: SqlQueryTest::TEST_TABLE_NAME)
          ->singleRow(columns: ['email', 'password'])
          ->values( [ 'user01@local.liferaftdev.com', 'liferaft' ] )->execute();
      $this->assertTrue( condition: $result->isOK() );
    }
  }

  public function testInsertMultipleRowsIntoATable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $query->init();
    $result =
      $query->insertInto(tableName: SqlQueryTest::TEST_TABLE_NAME)
        ->multipleRows(columns: ['email', 'password'])
        ->rows( [
            [ 'user02@local.liferaftdev.com', 'liferaft' ],
            [ 'user03@local.liferaftdev.com', 'liferaft' ],
            [ 'user04@local.liferaftdev.com', 'liferaft' ],
            [ 'user05@local.liferaftdev.com', 'liferaft' ],
          ] )
        ->execute();
    $this->assertTrue( condition: $result->isOK() );
  }

  public function testSelectAllRowsInATable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->select()->all()->from(table_references: SqlQueryTest::TEST_TABLE_NAME)->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testSelectFirstTwoRowsInATable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->select()->all()->from(table_references: SqlQueryTest::TEST_TABLE_NAME)->limit(limit: 2)->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testSelectTwoRowsSkippingOneRowInATable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->select()->all()->from(table_references: SqlQueryTest::TEST_TABLE_NAME)->limit(limit: 2, offset: 1)->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testSelectAllRowsWithSpecificColumnsInATable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->select()->all(columns: ['id', 'email'])->from(table_references: SqlQueryTest::TEST_TABLE_NAME)->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testSelectARowById(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->select()->all()->from( table_references: SqlQueryTest::TEST_TABLE_NAME )->where('id=1')->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testSelectARowByPredicate(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result =
      $query
        ->select()
        ->all()
        ->from( table_references: SqlQueryTest::TEST_TABLE_NAME )
        ->where("`email` LIKE '%04%'")
        ->and('id IN (1,2,3,4)')
        ->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testOrderSelectedRows(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result =
      $query
        ->select()
        ->all()
        ->from( table_references: SqlQueryTest::TEST_TABLE_NAME )
        ->orderBy([
          new SQLKeyPart( 'password', ascending: true ),
          new SQLKeyPart( 'id', ascending: false ),
          new SQLKeyPart( 'email' ),
        ])->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testGroupSelectedRows(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result =
      $query
        ->select()
        ->all()
        ->from( table_references: SqlQueryTest::TEST_TABLE_NAME )
        ->orderBy(['email'])
        ->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testUpdateARowWithGivenId(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result =
      $query
        ->update(tableName: SqlQueryTest::TEST_TABLE_NAME)
        ->set(['email' => 'user06@local.liferaftdev.com', 'password' => 'liferaft06'])
        ->where('id=5')
        ->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testUpdateMultipleRows(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result =
      $query
        ->update(tableName: SqlQueryTest::TEST_TABLE_NAME)
        ->set(['password' => true])
        ->execute();
    $this->assertTrue(condition: $result->isOK());
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