<?php

namespace LifeRaft\Database\Traits;

use LifeRaft\Database\Queries\SQLLimitClause;

trait SQLAggregatorTrait
{
  public function limit(int $limit, ?int $offset = null): mixed
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
   * @param array $key_parts A list of **SQLKeyPart** objects.
   */
  public function orderBy(array $key_parts): mixed
  {
    if (property_exists($this, 'query'))
    {
      $queryString = "ORDER BY " . implode(', ', $key_parts);
      $this->query->appendQueryString($queryString);
    }
    return $this;
  }

  public function groupBy(array $column_names): mixed
  {
    if (property_exists($this, 'query'))
    {
      $queryString = "GROUP BY " . implode(', ', $column_names);
      $this->query->appendQueryString($queryString);
    }
    return $this;
  }
}

?>