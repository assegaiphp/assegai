<?php

namespace LifeRaft\Database\Traits;

use LifeRaft\Database\Queries\SQLQueryResult;

trait ExecutableTrait
{
  public function execute(): SQLQueryResult
  {
    return $this->query->execute();
  }

  public function debug(): void
  {
    $this->query->debug();
  }
}

?>