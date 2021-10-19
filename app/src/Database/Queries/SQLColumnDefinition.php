<?php

namespace LifeRaft\Database\Queries;

class SQLColumnDefinition
{
  private string $sql = "";

  public function __construct(
    private string $name,
    private string $dataType = SQLDataTypes::INT,
    private string|int|null $dataTypeSize = null,
    private mixed $defaultValue = null,
    private bool $allowNull = true,
    private bool $autoIncrement = false,
    private string $onUpdate = "",
    private bool $isUnique = false,
    private string $uniqueKey = "",
    private bool $isPrimaryKey = false,
    private string $comment = "",
  )
  {
    $sql = "$this->name ";
    if (is_null($this->dataTypeSize))
    {
      $this->dataTypeSize = match($this->dataType) {
        SQLDataTypes::VARCHAR => '10',
        SQLDataTypes::DECIMAL => '16,2',
        default => null
      };
    }

    if (!is_null($this->dataTypeSize))
    {
      switch($this->dataType) {
        case SQLDataTypes::TINYINT:
        case SQLDataTypes::TINYINT_UNSIGNED:
        case SQLDataTypes::SMALLINT:
        case SQLDataTypes::SMALLINT_UNSIGNED:
        case SQLDataTypes::INT:
        case SQLDataTypes::INT_UNSIGNED:
        case SQLDataTypes::BIGINT:
        case SQLDataTypes::BIGINT_UNSIGNED:
        case SQLDataTypes::VARCHAR:
          $sql .= $this->dataType . "(" . $this->dataTypeSize . ") ";
          break;
  
        default: $sql .= "$this->dataType ";
      }
    }
    else
    {
      $sql .= "$this->dataType ";
    }

    if (!is_null($this->defaultValue))
    {
      $sql .= "DEFAULT " . match(gettype($this->defaultValue)) {
        'object' => method_exists($this->defaultValue, '__toString') ? strval($this->defaultValue) : json_encode($this->defaultValue),
        'boolean' => intval($this->defaultValue),
        default => $this->defaultValue
      } . " ";
    }
    if ($this->autoIncrement && SQLDataTypes::isNumeric($this->dataType))
    {
      $sql .= "AUTO_INCREMENT ";
    }
    $sql .= $this->allowNull && !$this->isPrimaryKey ? "NULL " : "NOT NULL ";
    if ($this->isPrimaryKey)
    {
      $sql .= "PRIMARY KEY ";
    }
    else if ($this->isUnique)
    {
      $sql .= trim("UNIQUE " . $this->uniqueKey) . ' ';
    }

    if (!empty($this->onUpdate))
    {
      $sql .= "ON UPDATE CURRENT_TIMESTAMP ";
    }

    if (!empty($this->comment))
    {
      $sql .= "COMMENT $this->comment ";
    }
    
    $this->sql = trim($sql);
  }

  public function sql(): string
  {
    return $this->sql;
  }

  public function __toString(): string
  {
    return $this->sql();
  }
}