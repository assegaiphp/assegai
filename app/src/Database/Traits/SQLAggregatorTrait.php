<?php

namespace LifeRaft\Database\Traits;

use LifeRaft\Database\Queries\SQLLimitClause;

trait SQLAggregatorTrait
{
  public function limit(int $limit, ?int $offset = null)
  {
    if (property_exists($this, 'query'))
    {
      return new SQLLimitClause(
        query: $this->query,
        limit: $limit,
        offset: $offset
      );
    }

    return $this;
  }

  /**
   * @param array $keyParts A list of **SQLKeyPart** objects.
   */
  public function orderBy(array $keyParts)
  {
    if (property_exists($this, 'query'))
    {
      $queryString = "ORDER BY " . implode(', ', $keyParts);
      $this->query->appendQueryString($queryString);
    }
    return $this;
  }

  public function groupBy(array $columnNames)
  {
    if (property_exists($this, 'query'))
    {
      $queryString = "GROUP BY " . implode(', ', $columnNames);
      $this->query->appendQueryString($queryString);
    }
    return $this;
  }
}

?>