<?php

namespace LifeRaft\Database\Traits;

use LifeRaft\Database\Queries\SQLInsertIntoMultipleStatement;
use LifeRaft\Database\Queries\SQLInsertIntoStatement;

trait DuplicateKeyUpdateableTrait
{
  public function onDuplicateKeyUpdate(array $assignmentList): SQLInsertIntoStatement|SQLInsertIntoMultipleStatement
  {
    $queryString = "";
    if (!empty($assignmentList))
    {
      $queryString .= "ON DUPLICATE KEY UPDATE ";
      foreach ($assignmentList as $assignment)
      {
        $queryString .= "$assignment ";
      }
    }
    $queryString = trim($queryString);
    $this->query->appendQueryString($queryString);
    return $this;
  }
}

?>