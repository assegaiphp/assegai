<?php

namespace LifeRaft\Database\Traits;

use LifeRaft\Database\Queries\SQLInsertIntoMultipleStatement;
use LifeRaft\Database\Queries\SQLInsertIntoStatement;

trait DuplicateKeyUpdateableTrait
{
  public function onDuplicateKeyUpdate(array $assignment_list): SQLInsertIntoStatement|SQLInsertIntoMultipleStatement
  {
    $sql = "";
    if (!empty($assignment_list))
    {
      $sql .= "ON DUPLICATE KEY UPDATE ";
      foreach ($assignment_list as $assignment)
      {
        $sql .= "$assignment ";
      }
    }
    $sql = trim($sql);
    $this->query->appendSQL($sql);
    return $this;
  }
}

?>