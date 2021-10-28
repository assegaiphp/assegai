<?php

namespace LifeRaft\Database;

use LifeRaft\Database\Attributes\Columns\CreateDateColumn;
use LifeRaft\Database\Attributes\Columns\DeleteDateColumn;
use LifeRaft\Database\Attributes\Columns\PrimaryGeneratedColumn;
use LifeRaft\Database\Attributes\Columns\UpdateDateColumn;
use LifeRaft\Database\Interfaces\IEntity;
use ReflectionClass;
use stdClass;

class BaseEntity implements IEntity
{
  #[PrimaryGeneratedColumn]
  public int $id = 0;

  #[CreateDateColumn]
  public string $createdAt = '';

  #[UpdateDateColumn]
  public string $updatedAt = '';
  
  #[DeleteDateColumn]
  public string $deletedAt = '';

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

  public function values(array $exclude = []): array
  {
    $values = [];
    $class = get_called_class();
    $columns = $class::columns(exclude: $exclude);

    foreach ($columns as $index => $column)
    {
      $value = is_numeric($index) ? $this->$column : $this->$index;
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
    $vars = get_object_vars($this);
    $array = [];
    foreach ($vars as $prop => $value)
    {
      if (!in_array($prop, $exclude))
      {
        $array[$prop] = $value;
      }
    }
    return $array;
  }

  public function toJSON(): string
  {
    return json_encode($this->toArray());
  }

  public function __toString(): string
  {
    return $this->toJSON();
  }

  public function __serialize(): array
  {
    return $this->toArray();
  }
}

?>