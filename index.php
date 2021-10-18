<?php

//use LifeRaft\Core\App;
//use LifeRaft\Core\Request;
use LifeRaft\Database\DBFactory;
use LifeRaft\Database\Queries\SQLColumnDefinition as Column;
use LifeRaft\Database\Queries\SQLDataTypes;
use LifeRaft\Database\Queries\SQLQuery;

require_once 'app/bootstrap.php';

$query = new SQLQuery( db: DBFactory::getMariaDBConnection( dbName: 'assegai_test' ) );
// $query->use(dbName: 'robo_monkeys');
// $query->describe(subject: 'users');
//$query->create()->table( tableName: 'test_users' );
// $query->create()->table( tableName: 'test_users_2', columns: [
//   new Column( name: 'id', dataType: SQLDataTypes::BIGINT_UNSIGNED, autoIncrement: true, isPrimaryKey: true ),
//   new Column( name: 'email', dataType: SQLDataTypes::VARCHAR, autoIncrement: true, isPrimaryKey: true ),
// ], checkIfNotExists: true );
// $query->insert()
exit($query);
// exit($query->execute());

//$request = new Request();
//$app = new App( request: $request, config: $config );
//$app->run();

