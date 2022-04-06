<?php

namespace Assegai\Database\Types;

/**
 * ON_UPDATE type to be used to specify update strategy when some relation is being updated.
 */
enum OnUpdateType: string
{
  case RESTRICT   = 'RESTRICT';
  case CASCADE    = 'CASCADE';
  case SET_NULL   = 'SET_NULL';
  case DEFAULT    = 'DEFAULT';
  case NO_ACTION  = 'NO_ACTION';
}

?>