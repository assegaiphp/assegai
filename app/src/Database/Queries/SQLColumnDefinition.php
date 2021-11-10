<?php

namespace Assegai\Database\Queries;

class SQLColumnDefinition
{
  private string $queryString = "";

  public function __construct(
    private string $name,
    private string $dataType = SQLDataTypes::INT,
    private null|string|int|array $lengthOrValues = null,
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
    $queryString = !empty($this->name) ? "`$this->name` " : '';
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
        case SQLDataTypes::SMALLINT:
        case SQLDataTypes::INT:
        case SQLDataTypes::BIGINT:
          $queryString .= $this->dataType;
          if (!empty($this->lengthOrValues))
          {
            $queryString .= "(" . $this->lengthOrValues . ") ";
          }
          else
          {
            $queryString .= " ";
          }
          break;
        case SQLDataTypes::TINYINT_UNSIGNED:
        case SQLDataTypes::SMALLINT_UNSIGNED:
        case SQLDataTypes::INT_UNSIGNED:
        case SQLDataTypes::BIGINT_UNSIGNED:
          $queryString .= $this->dataType;
          if (!empty($this->lengthOrValues))
          {
            $length = $this->lengthOrValues;
            $queryString = str_replace(' UNSIGNED', "($length) UNSIGNED ", $queryString);
          }
          else
          {
            $queryString .= " ";
          }
          break;
        case SQLDataTypes::VARCHAR:
          $queryString .= $this->dataType . "(" . $this->lengthOrValues . ") ";

          break;

        case SQLDataTypes::ENUM:
          if (!is_array($this->lengthOrValues))
          {
            $this->lengthOrValues = [];
          }
          $queryString .= $this->dataType . "(";
          foreach ($this->lengthOrValues as $value)
          {
            $queryString .= "'$value', ";
          }
          $queryString = trim($queryString, ', ');
          $queryString .= ") ";
          break;
  
        default: $queryString .= "$this->dataType ";
      }
    }
    else
    {
      $queryString .= "$this->dataType ";
    }

    if (SQLDataTypes::isNumeric($this->dataType) && is_string($this->defaultValue))
    {
      $this->defaultValue = $this->allowNull || $this->autoIncrement ? null : 0;
    }

    if (!is_null($this->defaultValue))
    {
      $temporalDatatypes = [
        // SQLDataTypes::DATE,
        SQLDataTypes::DATETIME
      ];
      $stringExamptions = ['CURRENT_TIMESTAMP'];
      $queryString .= "DEFAULT " . match(gettype($this->defaultValue)) {
        'object'  => method_exists($this->defaultValue, '__toString') ? strval($this->defaultValue) : json_encode($this->defaultValue),
        'boolean' => intval($this->defaultValue),
        // 'string'  => ( !in_array($this->dataType, $temporalDatatypes) ) ? "'" . $this->defaultValue . "'" : $this->defaultValue,
        'string'  => !in_array($this->defaultValue, $stringExamptions) ? "'" . $this->defaultValue . "'" : $this->defaultValue,
        default   => $this->defaultValue
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
    $queryString = str_replace('((', '(', $queryString);
    $queryString = str_replace('))', ')', $queryString);
    
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