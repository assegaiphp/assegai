<?php
declare(strict_types=1);

use LifeRaft\Database\DBFactory;
use LifeRaft\Database\Queries\SQLColumnDefinition as Column;
use LifeRaft\Database\Queries\SQLDataTypes;
use LifeRaft\Database\Queries\SQLKeyPart;
use LifeRaft\Database\Queries\SQLPrimaryGeneratedColumn as PrimaryColumn;
use LifeRaft\Database\Queries\SQLQuery;
use LifeRaft\Database\Schema;
use LifeRaft\Database\SchemaOptions;
use LifeRaft\Modules\Users\UserEntity;
use PHPUnit\Framework\TestCase;

final class SqlQueryTest extends TestCase
{
  const TEST_DB_NAME = 'assegai_test';
  const TEST_TABLE_NAME = 'unit_test';

  public function testEstablishDatabaseConnection(): void
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

  public function testAppendAStringToTheQueryString(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $str = 'SELECT *';
    $query->setQueryString($str);
    $tail = 'FROM `users`';
    $query->appendQueryString($tail);
    $this->assertEquals($str . ' ' . $tail, $query->queryString());
  }

  public function testCreateADatabase(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->create()->database(dbName: SqlQueryTest::TEST_DB_NAME . '_ut' )->execute();
    $this->assertTrue( condition: $result->isOK() );
  }

  // public function testRenameAMysqlDatabase(): void
  // {
  //   $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  //   $result = $query->rename()->database( from: SqlQueryTest::TEST_DB_NAME . '_ut', to: SqlQueryTest::TEST_DB_NAME . '_ut_renamed' )->execute();
  //   $this->assertTrue( condition: $result->isOK() );
  // }

  // public function testSelectADatabase(): void
  // {
  //   $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
  // }

  public function testDropADatabase(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->drop()->database(dbName: SqlQueryTest::TEST_DB_NAME . '_ut')->execute();
    $this->assertTrue( condition: $result->isOK() );
  }

  public function testCreateATable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->create()->table(
      tableName: SqlQueryTest::TEST_TABLE_NAME,
    )->columns(columns: [
      new PrimaryColumn(),
      new Column(name: 'email', dataType: SQLDataTypes::VARCHAR, lengthOrValues: 60, isUnique: true, allowNull: false),
      new Column(name: 'password', dataType: SQLDataTypes::TEXT)
    ])->execute();
    $this->assertTrue( condition: $result->isOK() );
  }
  
  public function testRenameATable(): void
  {
    $tableName = SqlQueryTest::TEST_TABLE_NAME;
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->rename()->table(from: $tableName, to: $tableName . '_renamed')->execute();
    $this->assertTrue( condition: $result->isOK() );
  }

  public function testDropATable(): void
  {
    $tableName = SqlQueryTest::TEST_TABLE_NAME;
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->drop()->table( tableName: $tableName . '_renamed' )->execute();
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
      new Column(name: 'email', dataType: SQLDataTypes::VARCHAR, lengthOrValues: 60, isUnique: true, allowNull: false),
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
    $result = $query->select()->all()->from(tableReferences: SqlQueryTest::TEST_TABLE_NAME)->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testSelectTheFirstTwoRowsInATable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->select()->all()->from(tableReferences: SqlQueryTest::TEST_TABLE_NAME)->limit(limit: 2)->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testSelectARangeOfRowsUsingOffsetAndLimit(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->select()->all()->from(tableReferences: SqlQueryTest::TEST_TABLE_NAME)->limit(limit: 2, offset: 1)->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testSelectAllRowsWithSpecificColumnsInATable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->select()->all(columns: ['id', 'email'])->from(tableReferences: SqlQueryTest::TEST_TABLE_NAME)->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testSelectARowById(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = $query->select()->all()->from( tableReferences: SqlQueryTest::TEST_TABLE_NAME )->where('id=1')->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testSelectARowByPredicate(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result =
      $query
        ->select()
        ->all()
        ->from( tableReferences: SqlQueryTest::TEST_TABLE_NAME )
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
        ->from( tableReferences: SqlQueryTest::TEST_TABLE_NAME )
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
        ->from( tableReferences: SqlQueryTest::TEST_TABLE_NAME )
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
    $result =
      $query->deleteFrom(tableName: SqlQueryTest::TEST_TABLE_NAME )->where('id=5')->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testDeleteMultipleRowsFromTheTable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result =
      $query->deleteFrom(tableName: SqlQueryTest::TEST_TABLE_NAME )->where('id=5')->or('id=2')->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testAlterTableByAddingAColumn(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = 
      $query->alter()->table( tableName: SqlQueryTest::TEST_TABLE_NAME )->addColumn( dataType: new Column( name: 'born_day', dataType: SQLDataTypes::DATE, defaultValue: date('Y-m-d'), allowNull: false ))->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testAlterTableByAddingAnEnumColumn(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = 
      $query->alter()->table( tableName: SqlQueryTest::TEST_TABLE_NAME )->addColumn( dataType: new Column( name: 'status', dataType: SQLDataTypes::ENUM, lengthOrValues: ['active', 'inactive', 'pending', 'archived', 'deleted'], defaultValue: 'active', allowNull: false ), afterColumn: 'isVerified' )->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testAlterTableByModifyingAColumn(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = 
      $query->alter()->table( tableName: SqlQueryTest::TEST_TABLE_NAME )->modifyColumn( dataType: new Column( name: 'born_day', dataType: SQLDataTypes::DATE, defaultValue: '1970-01-01', allowNull: false ))->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testAlterTableByRenamingAColumn(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = 
      $query->alter()->table( tableName: SqlQueryTest::TEST_TABLE_NAME )->renameColumn( oldColumnName: 'born_day', newColumnName: 'date_of_birth' )->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testAlterTableByDropingAColumn(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result = 
      $query->alter()->table( tableName: SqlQueryTest::TEST_TABLE_NAME )->dropColumn( columnName: 'date_of_birth' )->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testTruncateATable(): void
  {
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result =
      $query->truncateTable(tableName: SqlQueryTest::TEST_TABLE_NAME)->execute();
    $this->assertTrue(condition: $result->isOK());
  }

  public function testLeftJoinATable(): void
  {    
    $this->assertTrue(Schema::create(entityClass: UserEntity::class, options: new SchemaOptions(dbName: SqlQueryTest::TEST_DB_NAME)));
    
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result =
      $query->select()
        ->all()
        ->from(tableReferences: 'tests t')
        ->innerJoin(tableReferences: 'user_tests ut')
        ->on(searchCondition: 't.id = ut.test_id')
        ->innerJoin(tableReferences: 'users u')
        ->on(searchCondition: 'ut.user_id = u.id')
        ->execute();
    $this->assertTrue(condition: $result->isOK());
    $this->assertTrue(Schema::drop(entityClass: UserEntity::class, options: new SchemaOptions(dbName: 'assegai_test')));
  }

  public function testRightJoinATable(): void
  {    
    $query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: SqlQueryTest::TEST_DB_NAME ) );
    $result =
      $query->select()
        ->all()
        ->from(tableReferences: 'tests t')
        ->innerJoin(tableReferences: 'user_tests ut')
        ->on(searchCondition: 't.id = ut.test_id')
        ->innerJoin(tableReferences: 'users u')
        ->on(searchCondition: 'ut.user_id = u.id')
        ->execute();
    $this->assertTrue(condition: $result->isOK());
    $this->assertTrue(Schema::drop(entityClass: UserEntity::class, options: new SchemaOptions(dbName: 'assegai_test')));
  }
}

?>