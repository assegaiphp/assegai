<?php

namespace Assegai\Database\Types;

enum JoinType: string
{
  case JOIN             = 'JOIN';
  case STRAIGHT_JOIN    = 'STRAIGHT_JOIN';
  case LEFT_JOIN        = 'LEFT_JOIN';
  case RIGHT_JOIN       = 'RIGHT_JOIN';
  case LEFT_OUTER_JOIN  = 'LEFT_OUTER_JOIN';
  case RIGHT_OUTER_JOIN = 'RIGHT_OUTER_JOIN';
  case INNER_JOIN       = 'INNER_JOIN';
  case OUTER_JOIN       = 'OUTER_JOIN';
}

?>