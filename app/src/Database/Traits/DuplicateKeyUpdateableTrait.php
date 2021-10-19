<?php

namespace LifeRaft\Database\Traits;

use LifeRaft\Database\Queries\SQLInsertIntoMultipleStatement;
use LifeRaft\Database\Queries\SQLInsertIntoStatement;

trait DuplicateKeyUpdateableTrait
{
  public function onDuplicateKeyUpdate(array $assignment_list): SQLInsertIntoStatement|SQLInsertIntoMultipleStatement
  {
    $queryString = "";
    if (!empty($assignment_list))
    {
      $queryString .= "ON DUPLICATE KEY UPDATE ";
      foreach ($assignment_list as $assignment)
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