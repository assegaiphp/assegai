<?php

namespace Assegai\Database\Types;

/**
 * ON_DELETE type to be used to specify delete strategy when some relation is being deleted from the database.
 */
enum OnDeleteType: string
{
  case RESTRICT   = 'RESTRICT';
  case CASCADE    = 'CASCADE';
  case SET_NULL   = 'SET_NULL';
  case DEFAULT    = 'DEFAULT';
  case NO_ACTION  = 'NO_ACTION';
}

?>