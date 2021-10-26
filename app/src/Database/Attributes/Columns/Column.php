<?php

namespace LifeRaft\Database\Attributes\Columns;

use Attribute;
use LifeRaft\Database\Queries\SQLDataTypes;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Column
{
  const SIGNED = 'SIGNED';
  const UNSIGNED = 'UNSIGNED';
  const ZEROFILL = 'ZEROFILL';
  const NOW = 'NOW';
  const CURRENT_TIMESTAMP = 'CURRENT_TIMESTAMP';

  public string $value;

  public function __construct(
    public string $name = '',
    public string $alias = '',
    public string $dataType = SQLDataTypes::INT,
    private string|int|null $lengthOrValues = null,
    public bool $allowNull = true,
    public bool $signed = true,
    public bool $zeroFilled = false,
    public mixed $defaultValue = '',
    public bool $autoIncrement = false,
    public string $onUpdate = '',
    public bool $isUnique = false,
    public string $uniqueKey = '',
    public bool $isPrimaryKey = false,
    public string $comment = '',
    public bool $canUpdate = true
  )
  {
    $this->value = "$dataType ";

    if (!$signed)                 { $this->value .= Column::UNSIGNED . ' '; }
    if (!$allowNull)              { $this->value .= 'NOT '; }

    $this->value .= 'NULL ';

    if ($zeroFilled && !$signed)  { $this->value .= Column::ZEROFILL . ' '; }
    if (isset($defaultValue))     { $this->value .= "DEFAULT $defaultValue "; }

    if ($autoIncrement)           { $this->value .= "AUTO_INCREMENT "; }
    if ($isUnique)                { $this->value .= "UNIQUE {$uniqueKey}"; }

    if (isset($alias))            { $this->value .= "AS $alias"; }


    $this->value = trim($this->value);
  }
}

?>