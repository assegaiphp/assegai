<?php

namespace Assegai\Database\Types;

enum JoinType
{
  case JOIN;
  case STRAIGHT_JOIN;
  case LEFT_JOIN;
  case RIGHT_JOIN;
  case LEFT_OUTER_JOIN;
  case RIGHT_OUTER_JOIN;
  case INNER_JOIN;
  case OUTER_JOIN;
}

?>