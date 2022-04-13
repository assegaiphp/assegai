<?php

namespace Assegai\Database\Traits;

use Assegai\Database\Queries\SQLQueryResult;

trait ExecutableTrait
{
  public function execute(): SQLQueryResult
  {
    return $this->query->execute();
  }

  public function debug(): never
  {
    $this->query->debug();
  }
}

?>