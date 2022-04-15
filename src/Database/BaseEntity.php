<?php

namespace Assegai\Database;

use Assegai\Core\Debugger;
use Assegai\Core\Exceptions\ClassNotFoundException;
use Assegai\Core\Exceptions\GeneralSQLQueryException;
use Assegai\Core\Exceptions\IllegalTypeException;
use Assegai\Core\Responses\NotImplementedErrorResponse;
use Assegai\Database\Attributes\Columns\CreateDateColumn;
use Assegai\Database\Attributes\Columns\DeleteDateColumn;
use Assegai\Database\Attributes\Columns\PrimaryGeneratedColumn;
use Assegai\Database\Attributes\Columns\UpdateDateColumn;
use Assegai\Database\Attributes\Entity;
use Assegai\Database\Attributes\Relations\JoinColumn;
use Assegai\Database\Attributes\Relations\ManyToMany;
use Assegai\Database\Attributes\Relations\ManyToOne;
use Assegai\Database\Attributes\Relations\OneToMany;
use Assegai\Database\Attributes\Relations\OneToOne;
use Assegai\Database\Interfaces\IEntity;
use Assegai\Database\Types\SQLDialect;
use Assegai\Util\TextFormatter;
use ReflectionClass;
use ReflectionProperty;
use stdClass;

#[Entity]
class BaseEntity implements IEntity
{
  protected array $representations  = [];
  protected ?string $tableName      = null;
  protected ?string $orderBy        = null;
  protected ?string $engine         = null;
  protected ?string $database       = null;
  protected ?string $schema         = null;
  protected ?bool $synchronize      = true;
  protected ?bool $withRowId        = false;
  protected array $protected        = [];

  public function __construct()
  {
    $reflection = new ReflectionClass(objectOrClass: $this);
    $attributes = $reflection->getAttributes(Entity::class);

    foreach ($attributes as $entityAttribute)
    {
      $instance           = $entityAttribute->newInstance();
      $this->tableName    = $instance->tableName;
      $this->orderBy      = $instance->orderBy;
      $this->engine       = $instance->engine;
      $this->database     = $instance->database;
      $this->schema       = $instance->schema;
      $this->synchronize  = $instance->synchronize;
      $this->withRowId    = $instance->withRowId;
      $this->protected    = $instance->protected;
    }
  }

  #[PrimaryGeneratedColumn]
  public int $id = 0;

  #[CreateDateColumn]
  public string $createdAt = '';

  #[UpdateDateColumn]
  public string $updatedAt = '';
  
  #[DeleteDateColumn]
  public ?string $deletedAt = null;

  public function tableName(): ?string
  {
    return $this->tableName;
  }

  public function database(): ?string
  {
    return $this->database;
  }

  public static function newInstanceFromObject(stdClass $object): IEntity
  {
    $className = get_called_class();
    $entity = new $className;

    foreach ($object as $prop => $value)
    {
      if (property_exists($entity, $prop))
      {
        $entity->$prop = $value;
      }
    }

    return $entity;
  }

  public static function newInstanceFromArray(array $array): IEntity
  {
    $className = get_called_class();
    $entity = new $className;

    foreach ($array as $prop => $value)
    {
      if (property_exists($entity, $prop))
      {
        $entity->$prop = $value;
      }
    }

    return $entity;
  }

  public static function columns(array $exclude = []): array
  {
    $columns = [];

    $reflection = new ReflectionClass(objectOrClass: get_called_class());
    $props = $reflection->getProperties();

    foreach ($props as $prop)
    {
      if (!in_array($prop->getName(), $exclude))
      {
        # Get meta data
        $attributes = $prop->getAttributes();
        
        foreach ($attributes as $attribute)
        {
          $instance = $attribute->newInstance();
          if (!empty($instance->alias))
          {
            $columns[$instance->alias] = $instance->name;
          }
          else if(!empty($instance->name))
          {
            $columns[$prop->getName()] = $instance->name;
          }
          else
          {
            array_push($columns, $prop->getName());
          }
        }
      }
    }

    return $columns;
  }

  public static function isValidEntity(
    stdClass|IEntity $object,
    array $excludedFields = ['id', 'createdAt', 'deletedAt', 'updatedAt']
  ): bool
  {
    $isValid = true;

    $entityProps = get_class_vars(get_called_class());
    $objProps = get_object_vars($object);

    if (count($entityProps) !== count($objProps))
    {
      $isValid = false;
    }

    foreach ($object as $prop => $value)
    {
      if (!in_array($prop, $excludedFields))
      {
        if (!property_exists(get_called_class(), $prop))
        {
          $isValid = false;
        }
      }
    }

    return $isValid;
  }

  /**
   * This method is an alias for `$tableName`.
   * MongoDB stores documents in collections. Collections are analogous 
   * to tables in relational databases.
   * 
   * @return string Returns the collection name.
   */
  public function collectionName(): string
  {
    return $this->tableName;
  }

  public function values(array $exclude = []): array
  {
    $values = [];
    $class = get_called_class();
    $columns = $class::columns(exclude: $exclude);

    foreach ($columns as $index => $column)
    {
      $propName = is_numeric($index) ? $column : $index;
      $value = $this->$propName;
      if (empty($value))
      {
        $columnAttribute = new ReflectionProperty(get_class($this), $propName);
        $attributes = $columnAttribute->getAttributes();

        foreach ($attributes as $attribute)
        {
          $attrInstance = $attribute->newInstance();
          if (isset($attrInstance->defaultValue) && !empty($attrInstance->defaultValue))
          {
            $value = $attrInstance->defaultValue;
          }
        }
      }
      if (is_object($value) && property_exists($value, 'value'))
      {
        $value = $value->value;
      }
      array_push($values, $value);
    }

    return $values;
  }

  public function columnValuePairs(array $exclude = []): array
  {
    $values = [];
    $class = get_called_class();
    $columns = $class::columns(exclude: $exclude);
    $pairs = [];

    foreach ($columns as $alias => $column)
    {
      if (is_numeric($alias))
      {
        $pairs[$column] = $this->$column;
      }
      else
      {
        $pairs[$column] = $this->$alias;
      }
    }

    return $pairs;    
  }

  public function toArray(array $exclude = []): array
  {
    $columnAttributeTypesPath = dirname(__FILE__) . '/Attributes/Columns/ColumnTypes.php';

    $columnAttributeTypes = file_exists($columnAttributeTypesPath)
      ? require($columnAttributeTypesPath)
      : [];
    if (empty($columnAttributeTypes))
    {
      Debugger::log("Empty column attribute types in " . __FILE__ . ' ('. __LINE__ . ')');
    }
    $vars = get_object_vars($this);
    $array = [];
    if (!empty($this->protected))
    {
      $exclude = array_merge($exclude, $this->protected);
    }
    foreach ($vars as $prop => $value)
    {
      if (!in_array($prop, $exclude))
      {
        $propReflection = new ReflectionProperty(class: $this, property: $prop);
        $propAttributes = $propReflection->getAttributes();

        foreach($propAttributes as $attribute)
        {
          if (in_array($attribute->getName(), $columnAttributeTypes))
          {
            $array[$prop] = $value;
          }
        }
      }
    }
    return $array;
  }

  public function toJSON(): string
  {
    return json_encode($this->toArray());
  }

  public function toPlainObject(): stdClass
  {
    return json_decode($this->toJSON());
  }

  public function schema(string|SQLDialect $dialect = 'mysql'): string
  {
    $keys = [];
    $constraints = [];
    $statement = '';
    $reflection = new ReflectionClass(objectOrClass: $this);
    $properties = $reflection->getProperties(filter: ReflectionProperty::IS_PUBLIC);

    $statement = "CREATE TABLE IF NOT EXISTS `$this->tableName` (";

    switch ($dialect)
    {
      case 'mysql':
      default:
        foreach ($properties as $property)
        {
          $attributes = $property->getAttributes();

          foreach ($attributes as $attribute)
          {
            $instance = $attribute->newInstance();

            if (str_ends_with($attribute->getName(), 'Column') && !str_ends_with($attribute->getName(), 'JoinColumn'))
            {
              if (empty($instance->name))
              {
                $propName = $property->getName();
                $statement .= "`$propName`" . " ";
              }
              $statement .= $instance->sqlDefinition . ", ";
            }
            else if ($this->isRelationAttribute(attributeClassName: $attribute->getName()))
            {
              switch($attribute->getName())
              {
                case OneToOne::class:
                  if ($this->hasJoinColumnAttribute(attributes: $attributes))
                  {
                    $joinColumnAttr = $property->getAttributes(name: JoinColumn::class)[0] ?? null;

                    if (empty($joinColumnAttr))
                    {
                      throw new ClassNotFoundException(className: JoinColumn::class);
                    }

                    $joinColumnAttrInstance = $joinColumnAttr->newInstance();
                    $name = $joinColumnAttrInstance->name ?? $property->getName();
                    $referencedColumn = $joinColumnAttrInstance->referencedColumn ?? 'id';
                    $joinColumnName = TextFormatter::toCamelCase($name, $referencedColumn);

                    $referencedTable = $this->getReferencedTableName(entityClass: $instance->type);
                    $statement .= "`$joinColumnName` " . $this->getReferencedColumnDefinition(entityClass: $instance->type, property: $referencedColumn);

                    $hash = md5($joinColumnName . time());
                    $generatedKeyName = "IDX_{$hash}";
                    $generatedFKName = "FK_{$hash}";
                    # If this is join column, create column and add index
                    $keys[] = "KEY `$generatedKeyName` (`$joinColumnName`)";
                    # Create constraint
                    $constraints[] = "CONSTRAINT `$generatedFKName` FOREIGN KEY (`$joinColumnName`) REFERENCES `$referencedTable` (`$referencedColumn`)";
                  }
                  break;

                case OneToMany::class:
                  break;

                case ManyToOne::class:
                  break;

                case ManyToMany::class:
                  break;
              }
            }
          }
        }

        # Append keys and indexes
        foreach ($keys as $key)
        {
          $statement .= "$key, ";
        }

        # Append constraints
        foreach ($constraints as $constraint)
        {
          $statement .= "$constraint, ";
        }
        break;

      case 'pgsql':
      case 'postgres':
        exit(new NotImplementedErrorResponse(message: 'PostreSQL schemas not yet supported'));
        break;

      case 'sqlite':
        exit(new NotImplementedErrorResponse(message: 'SQLite schemas not yet supported'));
        break;
    }
    $statement = trim($statement, ', ');
    $statement .= ")";

    return trim($statement);
  }

  public function tableExists(DataSource $dataSource): bool
  {
    return Schema::dbTableExists(
      dataSource: $dataSource,
      databaseName: $this->database,
      tableName: $this->tableName,
      dialect: $dataSource->type
    );
  }

  public function getTableName(): string
  {
    return $this->tableName;
  }

  public function __toString(): string
  {
    return $this->toJSON();
  }

  public function __serialize(): array
  {
    return $this->toArray();
  }

  private function isRelationAttribute(string $attributeClassName): bool
  {
    return in_array($attributeClassName, [
      OneToOne::class,
      OneToMany::class,
      ManyToOne::class,
      ManyToMany::class,
    ]);
  }

  /**
   * @param string $attributeClassName The name of the attribute cl
   */
  private function hasAttribute(string $attributeClassName, array $attributes): bool
  {
    return in_array($attributeClassName, $attributes);
  }

  /**
   * @param ReflectionAttribute[] $attributes
   */
  private function hasJoinColumnAttribute(array $attributes): bool
  {
    $found = false;

    foreach ($attributes as $attribute)
    {
      if ($attribute->getName() === JoinColumn::class)
      {
        $found = true;
      }
    }

    return $found;
  }

  /**
   * @param ReflectionAttribute[] $attributes
   */
  private function hasJoinTAttribute(array $attributes): bool
  {
    return in_array(JoinColumn::class, $attributes);
  }

  private function getReferencedTableName(string $entityClass): string
  {
    if (!is_a($entityClass, IEntity::class, true))
    {
      throw new IllegalTypeException(expected: IEntity::class, actual: $entityClass);
    }

    $entity = new $entityClass;

    return $entity->getTableName();
  }

  private function getReferencedColumnDefinition(string $entityClass, string $property): string
  {
    $definition = '';
    if (!property_exists($entityClass, $property))
    {
      throw new GeneralSQLQueryException();
    }

    $reflection = new ReflectionClass($entityClass);
    $reflectionProp = $reflection->getProperty($property);

    $attributes = $reflectionProp->getAttributes();

    foreach ($attributes as $attribute)
    {
      if (str_ends_with($attribute->getName(), 'Column') && !str_ends_with($attribute->getName(), 'JoinColumn'))
      {
        $instance = $attribute->newInstance();

        $definition .= $instance->sqlDefinition . ", ";
      }
    }

    return preg_replace('/(PRIMARY KEY)|(AUTO_INCREMENT\s*)/', '', substr($definition, strpos($definition, '` ') + 2));
  }
}

