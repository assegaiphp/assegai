<?php

namespace Assegai\Util;

use Assegai\Database\Attributes\Columns\CreateDateColumn;
use Assegai\Database\Attributes\Columns\DeleteDateColumn;
use Assegai\Database\Attributes\Columns\UpdateDateColumn;
use Assegai\Database\Interfaces\IEntity;
use ReflectionClass;
use ReflectionProperty;

final class Filter
{
  public static function getCreateDateColumnName(IEntity $entity): string
  {
    $instance = new ReflectionClass($entity);
    $properties = $instance->getProperties(ReflectionProperty::IS_PUBLIC);

    $name = '';

    foreach ($properties as $prop)
    {
      if (in_array($prop->getName(), ['createdAt', 'created_at']))
      {
        $name = $prop->getName();
        
        $attributes = $prop->getAttributes(name: CreateDateColumn::class);

        foreach ($attributes as $columnAttribute)
        {
          $columnAttributeInstance = $columnAttribute->newInstance();
          if (!empty($columnAttributeInstance->name))
          {
            $name = $columnAttributeInstance->name;
          }
        }
      }
    }

    return $name;
  }

  public static function getUpdateDateColumnName(IEntity $entity): string
  {
    $instance = new ReflectionClass($entity);
    $properties = $instance->getProperties(ReflectionProperty::IS_PUBLIC);

    $name = '';

    foreach ($properties as $prop)
    {
      if (in_array($prop->getName(), ['updatedAt', 'updated_at']))
      {
        $name = $prop->getName();
        
        $attributes = $prop->getAttributes(name: UpdateDateColumn::class);

        foreach ($attributes as $columnAttribute)
        {
          $columnAttributeInstance = $columnAttribute->newInstance();
          if (!empty($columnAttributeInstance->name))
          {
            $name = $columnAttributeInstance->name;
          }
        }
      }
    }

    return $name;
  }

  public static function getDeleteDateColumnName(IEntity $entity): string
  {
    $instance = new ReflectionClass($entity);
    $properties = $instance->getProperties(ReflectionProperty::IS_PUBLIC);

    $name = '';

    foreach ($properties as $prop)
    {
      if (in_array($prop->getName(), ['deletedAt', 'deleted_at']))
      {
        $name = $prop->getName();
        
        $attributes = $prop->getAttributes(name: DeleteDateColumn::class);

        foreach ($attributes as $columnAttribute)
        {
          $columnAttributeInstance = $columnAttribute->newInstance();
          if (!empty($columnAttributeInstance->name))
          {
            $name = $columnAttributeInstance->name;
          }
        }
      }
    }

    return $name;
  }
}