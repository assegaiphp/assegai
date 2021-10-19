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
      $sql .= "ON DUPLICATE KEY UPDATE ";
      foreach ($assignment_list as $assignment)
      {
        $sql .= "$assignment ";
      }
    }
    $queryString = trim($sql);
    $this->query->appendQueryString($sql);
    return $this;
  }
}

?>