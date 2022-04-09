<?php

namespace Assegai\Database\Types;

/**
 * DEFERRABLE type to be used to specify if foreign key constraints can be deferred.
 */
enum DeferrableType: string
{
  case INITIALLY_IMMEDIATE  = 'INITIALLY_IMMEDIATE';
  case INITIALLY_DEFERRED   = 'INITIALLY_DEFERRED';
}

?>