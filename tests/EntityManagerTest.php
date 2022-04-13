<?php

declare(strict_types=1);

use Assegai\Database\Attributes\Columns\Column;
use Assegai\Database\Attributes\Entity;
use Assegai\Database\BaseEntity;
use Assegai\Database\DataSource;
use Assegai\Database\DataSourceOptions;
use Assegai\Database\Interfaces\IEntity;
use Assegai\Database\Management\EntityManager;
use Assegai\Database\Management\FindOneOptions;
use Assegai\Database\Management\FindOptions;
use Assegai\Database\Management\FindWhereOptions;
use Assegai\Database\Queries\SQLDataTypes;
use Assegai\Database\Types\DataSourceType;
use PHPUnit\Framework\TestCase;

define('DATABASE_NAME', 'assegai_test');
define('TABLE_NAME', 'tests');

#[Entity(tableName: TABLE_NAME, database: DATABASE_NAME)]
class MockEntity extends BaseEntity implements IEntity
{
  #[Column(dataType: SQLDataTypes::VARCHAR, lengthOrValues: 100)]
  public string $name = '';

  #[Column(dataType: SQLDataTypes::TEXT, allowNull: true, defaultValue: null)]
  public string $description = '';

  #[Column(dataType: SQLDataTypes::VARCHAR, lengthOrValues: 100, allowNull: true, defaultValue: null)]
  public string $value = '';
}

function randomNoun(): string {
  $nouns = require(__DIR__ . '/data/english-nouns.php') ?? [
    'people',
    'history',
    'way',
    'art',
    'world',
    'information',
    'map',
    'two',
    'family',
    'government',
  ];

  return $nouns[rand(0, count($nouns) - 1)];
}

final class EntityManagerTest extends TestCase
{
  private readonly DataSourceOptions $datasourceOptions;

  public function __construct(?string $name = null, array $data = [], $dataName = '')
  {
    parent::__construct(name: $name, data: $data, dataName: $dataName);
    $config = file_exists(__DIR__ . '/data/config.php')
      ? require(__DIR__ . '/data/config.php')
      : [];

    $this->datasourceOptions = new DataSourceOptions(
      type: $config['type'] ?? DataSourceType::MARIADB,
      host: $config['host'] ?? 'localhost',
      port: $config['port'] ?? 3306,
      database: $config['database'] ?? DATABASE_NAME,
      username: $config['username'] ?? 'root',
      password: $config['password'] ?? ''
    );
  }

  public function testShouldCreateAnInstance(): void
  {
    $datasource = new DataSource(options: $this->datasourceOptions);
    $this->assertInstanceOf(EntityManager::class, $datasource->manager);
  }

  public function testShouldCreateAndSaveAnEntity(): void
  {
    $datasource = new DataSource(options: $this->datasourceOptions);
    $entity = $datasource->manager->create(entityClass: MockEntity::class);

    $entity->name = randomNoun();
    $entity->description = 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptates, fuga.';

    $this->assertInstanceOf(IEntity::class, $entity);

    $saveResult = $datasource->manager->save(targetOrEntity: $entity);

    $this->assertTrue(is_array($saveResult) || $saveResult instanceof IEntity);
  }

  public function testShouldExecuteARawSqlQuery(): void
  {
    $datasource = new DataSource(options: $this->datasourceOptions);
    $name = randomNoun();
    $value = randomNoun();

    $result = $datasource->manager->query("INSERT INTO tests (name, value) VALUES('$name', '$value')");

    $this->assertNotFalse($result);
  }

  public function testShouldMergeMultipleEntitiesIntoASingleEntity(): void
  {
    $datasource = new DataSource(options: $this->datasourceOptions);
    $name = 'harry 1';
    $value = 'v2';
    $entity = $datasource->manager->merge(MockEntity::class, ['name' => $name], ['value' => $value]);

    $this->assertInstanceOf(IEntity::class, $entity);
    $this->assertTrue($entity->name === $name);
    $this->assertTrue($entity->value === $value);
  }

  public function testShouldCreateANewEntityFromAGivenPlainPhpObject(): void
  {
    $datasource = new DataSource(options: $this->datasourceOptions);
    $entityLike = new stdClass;
    $name = 'name 555';
    $description = 'description 555';
    $value = 'value 555';

    $entityLike->name = $name;
    $entityLike->description = $description;
    $entityLike->value = $value;

    $entity = $datasource->manager->preload(entityClass: MockEntity::class, entityLike: $entityLike);

    $this->assertInstanceOf(IEntity::class, $entity);
    $this->assertEquals($entityLike->name, $entity->name);
    $this->assertEquals($entityLike->description, $entity->description);
    $this->assertEquals($entityLike->value, $entity->value);
  }

  public function testShouldInsertAGivenEntityIntoTheDatabase(): void
  {
    $datasource = new DataSource(options: $this->datasourceOptions);

    $entity = $datasource->manager->create(entityClass: MockEntity::class);
    $entity->name = randomNoun();
    $entity->value = randomNoun();
    $entity->description = 'Lorem ipsum dolor, sit amet consectetur adipisicing ' .
      'elit. Laborum rem ullam, magnam distinctio error beatae. Architecto ' .
      'obcaecati facere corrupti quos quia dolor rerum exercitationem nesciunt, ' .
      'veritatis enim, harum blanditiis vel!';

    $result = $datasource->manager->insert(entityClass: MockEntity::class, entity: $entity);

    $this->assertTrue($result->isOK());
  }

  public function testShouldPartiallyUpdateAGivenEntityInTheDatabase(): void
  {
    $datasource = new DataSource(options: $this->datasourceOptions);

    $entity = $datasource->manager->create(entityClass: MockEntity::class, plainObjectOrObjects: ['name' => randomNoun()]);

    $result = $datasource->manager->update(entityClass: MockEntity::class, conditions: ['id' => 1], entity: $entity);

    $this->assertTrue($result->isOK());
  }

  public function testShouldRemoveAGivenEntityFromTheDatabase(): void
  {
    # Insert an entity
    $datasource = new DataSource(options: $this->datasourceOptions);

    $entityDetails = [
      'name' => randomNoun(),
      'value' => randomNoun(),
    ];
    $entity = $datasource->manager->create(entityClass: MockEntity::class, plainObjectOrObjects: $entityDetails);

    $insertionResult = $datasource->manager->insert(entityClass: MockEntity::class, entity: $entity);
    $this->assertTrue($insertionResult->isOK());

    # Then remove said entity
    if ($insertionResult->isOK())
    {
      $lastInsertedEntity =
        $datasource->manager->findOne(
          entityClass: MockEntity::class,
          options: new FindOneOptions( where: new FindWhereOptions( ['id' => $datasource->manager->lastInsertId()] ))
        );
      $removalResult = $datasource->manager->remove(entityOrEntities: $lastInsertedEntity);

      $this->assertNotNull($removalResult);
    }
  }

  public function testShouldRecordTheDeletionDateOfAGivenEntityInTheDatabase(): void
  {
    # Insert an entity
    $datasource = new DataSource(options: $this->datasourceOptions);

    $entityDetails = [
      'name' => randomNoun(),
      'value' => randomNoun(),
    ];
    $entity = $datasource->manager->create(entityClass: MockEntity::class, plainObjectOrObjects: $entityDetails);

    $insertionResult = $datasource->manager->insert(entityClass: MockEntity::class, entity: $entity);
    $this->assertTrue($insertionResult->isOK());

    # Then soft remove said entity
    if ($insertionResult->isOK())
    {
      $lastInsertedEntity =
        $datasource->manager->findOne(
          entityClass: MockEntity::class,
          options: new FindOneOptions( where: new FindWhereOptions( ['id' => $datasource->manager->lastInsertId()] ))
        );
      $removalResult = $datasource->manager->softRemove(entityOrEntities: $lastInsertedEntity);

      $this->assertNotNull($removalResult);
    }
  }

  public function testShouldDeleteEntitiesByGivenConditions(): void
  {
    # Insert an entity
    $datasource = new DataSource(options: $this->datasourceOptions);

    $entityDetails = [
      'name' => randomNoun(),
      'value' => randomNoun(),
    ];
    $entity = $datasource->manager->create(entityClass: MockEntity::class, plainObjectOrObjects: $entityDetails);

    $insertionResult = $datasource->manager->insert(entityClass: MockEntity::class, entity: $entity);
    $this->assertTrue($insertionResult->isOK());

    # Then delete said entity
    if ($insertionResult->isOK())
    {
      $removalResult = $datasource->manager->delete(entityClass: MockEntity::class, conditions: ['id' => $datasource->manager->lastInsertId()]);

      $this->assertNotNull($removalResult);
    }
  }

  public function testShouldRestoreAnEntityByGivenConditions(): void
  {
    # Insert an entity
    $datasource = new DataSource(options: $this->datasourceOptions);

    $entityDetails = [
      'name' => randomNoun(),
      'value' => randomNoun(),
    ];
    $entity = $datasource->manager->create(entityClass: MockEntity::class, plainObjectOrObjects: $entityDetails);

    $insertionResult = $datasource->manager->insert(entityClass: MockEntity::class, entity: $entity);
    $this->assertTrue($insertionResult->isOK());

    # Then soft remove said entity
    if ($insertionResult->isOK())
    {
      $lastInsertedEntity =
        $datasource->manager->findOne(
          entityClass: MockEntity::class,
          options: new FindOneOptions( where: new FindWhereOptions( ['id' => $datasource->manager->lastInsertId()] ))
        );
      $removalResult = $datasource->manager->softRemove(entityOrEntities: $lastInsertedEntity);

      $this->assertNotNull($removalResult);

      # Then restore said entity
      $restoreResult =
        $datasource->manager->restore(
          entityClass: MockEntity::class,
          conditions: ['id' => $datasource->manager->lastInsertId()]
        );

      $this->assertNotNull($restoreResult);
    }
  }

  public function testShouldCountEntitiesThatMatchGivenOptions(): void
  {
    $datasource = new DataSource(options: $this->datasourceOptions);
    $findOptions = new FindOptions(where: new FindWhereOptions(conditions: ['value' => 'value1']));

    $count = $datasource->manager->count(entityClass: MockEntity::class, options: $findOptions);

    $this->assertIsInt($count);
    $this->assertTrue($count > 0);
  }

  public function testShouldFindAllEntitiesOfAGivenType(): void
  {
    $datasource = new DataSource(options: $this->datasourceOptions);

    $entities = $datasource->manager->find(entityClass: MockEntity::class);

    $this->assertIsArray($entities);
  }

  public function testShouldFindEntitiesThatMatchTheGivenFindOptions(): void
  {
    $datasource = new DataSource(options: $this->datasourceOptions);

    $findOptions = new FindOptions();

    $entities = $datasource->manager->find(entityClass: MockEntity::class, findOptions: $findOptions);

    $this->assertIsArray($entities);
  }
}