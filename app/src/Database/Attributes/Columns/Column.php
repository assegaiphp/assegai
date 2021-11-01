<?php

namespace LifeRaft\Database\Attributes\Columns;

use Attribute;
use LifeRaft\Database\Queries\SQLColumnDefinition;
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
  public string $sqlDefinition = '';

  public function __construct(
    public string $name = '',
    public string $alias = '',
    public string $dataType = SQLDataTypes::INT,
    public null|string|array|int $lengthOrValues = null,
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

    # Build definition string
    $sqlLengthOrValues = $this->lengthOrValues;
    if (is_null($sqlLengthOrValues)) {
      $sqlLengthOrValues = match ($this->dataType) {
        SQLDataTypes::VARCHAR => '10',
        SQLDataTypes::DECIMAL => '16,2',
        default => null
      };
    }

    $this->sqlDefinition = new SQLColumnDefinition(
      name: $this->name,
      dataType: $this->dataType,
      lengthOrValues: $sqlLengthOrValues,
      defaultValue: $this->defaultValue,
      allowNull: $this->allowNull,
      autoIncrement: $this->autoIncrement,
      onUpdate: $this->onUpdate,
      isUnique: $this->isUnique,
      isPrimaryKey: $this->isPrimaryKey,
      comment: $this->comment
    );

    $this->lengthOrValues = match(gettype($this->lengthOrValues)) {
      'array' => empty($this->lengthOrValues) ? '' : '(' . implode(',', $this->lengthOrValues) . ')',
      'NULL'  => '',
      default => empty($this->lengthOrValues) ? '' : '(' . $this->lengthOrValues  . ')'
    };

    $this->value = "${dataType}$this->lengthOrValues ";

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