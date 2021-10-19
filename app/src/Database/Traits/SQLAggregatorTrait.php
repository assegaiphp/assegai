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

  public function orderBy(array $column_list): mixed
  {
    if (property_exists($this, 'query'))
    {

    }
    return $this;
  }

  public function groupBy(): mixed
  {
    if (property_exists($this, 'query'))
    {
      
    }
    return $this;
  }
}

?>