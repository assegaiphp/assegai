<?php

namespace Assegai\Database\Traits;

use Assegai\Database\Queries\SQLInsertIntoMultipleStatement;
use Assegai\Database\Queries\SQLInsertIntoStatement;

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