<?php

namespace Assegai\Database\Queries;

final class SQLSelectDefinition
{
  public function __construct(
    private SQLQuery $query
  ) {
    $queryString = "SELECT ";
    $this->query->setQueryString(queryString: $queryString);
  }

  public function all(array $columns = []): SQLSelectExpression
  {
    $queryString = $this->getColumnListString(columns: $columns);

    $this->query->appendQueryString($queryString);

    return new SQLSelectExpression( query: $this->query );
  }

  public function count(array $columns = []): SQLSelectExpression
  {
    $queryString = "COUNT(" . $this->getColumnListString(columns: $columns) . ')';

    $this->query->appendQueryString($queryString);

    return new SQLSelectExpression( query: $this->query );
  }

  public function avg(array $columns = []): SQLSelectExpression
  {
    $queryString = "AVG(" . $this->getColumnListString(columns: $columns) . ')';

    $this->query->appendQueryString($queryString);

    return new SQLSelectExpression( query: $this->query );
  }

  public function sum(array $columns = []): SQLSelectExpression
  {
    $queryString = "SUM(" . $this->getColumnListString(columns: $columns) . ')';

    $this->query->appendQueryString($queryString);

    return new SQLSelectExpression( query: $this->query );
  }

  /**
   * Creates and returns a list of comma-separatred column names from a given 
   * array of strings.
   * 
   * @param array<string> $columns The list of column names.
   * 
   * @return string Returns a list of comma-separated column names if the 
   * given array is not empty, otherwise returns `*`.
   */
  private function getColumnListString(array $columns): string
  {
    $columnListString = '';
    $separator = ', ';

    if (empty($columns))
    {
      $columnListString .= "*";
    }
    else
    {
      foreach ($columns as $key => $value)
      {
        $columnListString .= is_numeric($key) ? "${value}${separator}" : "$value as ${key}${separator}";
      }
    }
    $columnListString = trim($columnListString, $separator);

    return $columnListString;
  }
}

?>