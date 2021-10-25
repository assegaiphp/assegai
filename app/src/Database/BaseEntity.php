<?php

namespace LifeRaft\Database;

use LifeRaft\Database\Attributes\Columns\Column;
use LifeRaft\Database\Interfaces\IEntity;
use ReflectionClass;

class BaseEntity implements IEntity
{
  public static function columns(array $exclude = []): array
  {
    $columns = [];

    $reflection = new ReflectionClass(objectOrClass: get_called_class());
    $props = $reflection->getProperties();

    foreach ($props as $prop)
    {
      if (!in_array($prop, $exclude))
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
}

?>