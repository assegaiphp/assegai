<?php

use Assegai\Database\Attributes\Columns\Column;
use Assegai\Database\Attributes\Entity;
use Assegai\Database\BaseEntity;
use Assegai\Database\Interfaces\IEntity;
use Assegai\Database\Queries\SQLDataTypes;
use Assegai\Database\Schema;
use Assegai\Database\SchemaOptions;
use PHPUnit\Framework\TestCase;

defined('DATABASE_NAME') or define('DATABASE_NAME', 'assegai_test');
defined('TABLE_NAME') or define('TABLE_NAME', 'examples');

$config = [
  'databases' => [
    'mysql' => [
      'assegai_test' => [
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'assegai_test',
        'user' => 'root',
        'password' => ''
      ]
    ]
  ]
];

#[Entity(tableName: TABLE_NAME, database: DATABASE_NAME)]
class MockEntity extends BaseEntity implements IEntity
{
  #[Column(dataType: SQLDataTypes::VARCHAR, lengthOrValues: 100)]
  public string $name = '';

  #[Column(dataType: SQLDataTypes::TEXT, allowNull: true, defaultValue: null)]
  public string $description = '';

  #[Column(dataType: SQLDataTypes::VARCHAR, lengthOrValues: 100, allowNull: true, defaultValue: null)]
  public string $value = '';

  #[Column(dataType: SQLDataTypes::VARCHAR, lengthOrValues: 100)]
  public string $rubbish = '';
}


final class SchemaTest extends TestCase
{

  public function __construct(?string $name = null, array $data = [], $dataName = '')
  {
    parent::__construct(name: $name, data: $data, dataName: $dataName);
    $config = file_exists(__DIR__ . '/data/config.php')
      ? require(__DIR__ . '/data/config.php')
      : [];
    
    unset($config['type']);
    $config['name'] = $config['database'];

    $GLOBALS['config']['databases']['mysql'][DATABASE_NAME] = $config;
  }

  public function testShouldCreateATable(): void
  {
    $creationResult = Schema::create(entityClass: MockEntity::class, options: new SchemaOptions(dbName: DATABASE_NAME));
    $this->assertTrue($creationResult);
  }

  public function testShouldDropATableIfExistsThenCreateATable(): void
  {
    $creationResult = Schema::create(entityClass: MockEntity::class, options: new SchemaOptions(dbName: DATABASE_NAME, dropSchema: true));
    $this->assertTrue($creationResult);
  }

  public function testShouldRenameATable(): void {}

  public function testShouldUpdateATable(): void {}

  public function testShouldGetInformationAboutATable(): void {}

  public function testShouldEmptyOrTruncateATable(): void {}

  public function testShouldDropATable(): void
  {
    // $dropResult = Schema::drop(entityClass: MockEntity::class, options: new SchemaOptions(dbName: DATABASE_NAME));
    // $this->assertTrue($dropResult);
  }
}

?>