<?php

namespace LifeRaft\Database\Queries;

class SQLColumnDefinition
{
  private string $queryString = "";

  public function __construct(
    private string $name,
    private string $dataType = SQLDataTypes::INT,
    private string|int|null $lengthOrValues = null,
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
    $queryString = "`$this->name` ";
    if (is_null($this->lengthOrValues))
    {
      $this->lengthOrValues = match($this->dataType) {
        SQLDataTypes::VARCHAR => '10',
        SQLDataTypes::DECIMAL => '16,2',
        default => null
      };
    }

    if (!is_null($this->lengthOrValues))
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
          $queryString .= $this->dataType . "(" . $this->lengthOrValues . ") ";
          break;
  
        default: $queryString .= "$this->dataType ";
      }
    }
    else
    {
      $queryString .= "$this->dataType ";
    }

    if (!is_null($this->defaultValue))
    {
      $temporalDatatypes = [
        // SQLDataTypes::DATE,
        SQLDataTypes::DATETIME
      ];
      $queryString .= "DEFAULT " . match(gettype($this->defaultValue)) {
        'object' => method_exists($this->defaultValue, '__toString') ? strval($this->defaultValue) : json_encode($this->defaultValue),
        'boolean' => intval($this->defaultValue),
        'string' => ( !in_array($this->dataType, $temporalDatatypes) ) ? "'" . $this->defaultValue . "'" : $this->defaultValue,
        default => $this->defaultValue
      } . " ";
    }
    if ($this->autoIncrement && SQLDataTypes::isNumeric($this->dataType))
    {
      $queryString .= "AUTO_INCREMENT ";
    }
    $queryString .= $this->allowNull && !$this->isPrimaryKey ? "NULL " : "NOT NULL ";
    if ($this->isPrimaryKey)
    {
      $queryString .= "PRIMARY KEY ";
    }
    else if ($this->isUnique)
    {
      $queryString .= trim("UNIQUE " . $this->uniqueKey) . ' ';
    }

    if (!empty($this->onUpdate))
    {
      $queryString .= "ON UPDATE CURRENT_TIMESTAMP ";
    }

    if (!empty($this->comment))
    {
      $queryString .= "COMMENT $this->comment ";
    }
    
    $this->queryString = trim($queryString);
  }

  public function queryString(): string
  {
    return $this->queryString;
  }

  public function __toString(): string
  {
    return $this->queryString();
  }
}