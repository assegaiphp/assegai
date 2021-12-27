<?php

namespace Assegai\Database\Types;

/* TODO: Change to enum with PHP 8.1 */
final class JoinType
{
  public function __construct(
    private ?string $value = null
  ) { }

  public static function JOIN(): JoinType
  {
    return new JoinType(value: 'join');
  }

  public static function STRAIGHT_JOIN(): JoinType
  {
    return new JoinType(value: 'straight_join');
  }

  public static function LEFT_JOIN(): JoinType
  {
    return new JoinType(value: 'left_join');
  }

  public static function RIGHT_JOIN(): JoinType
  {
    return new JoinType(value: 'right_join');
  }

  public static function LEFT_OUTER_JOIN(): JoinType
  {
    return new JoinType(value: 'left_outer_join');
  }

  public static function RIGHT_OUTER_JOIN(): JoinType
  {
    return new JoinType(value: 'right_outer_join');
  }

  public static function INNER_JOIN(): JoinType
  {
    return new JoinType(value: 'inner_join');
  }

  public static function OUTER_JOIN(): JoinType
  {
    return new JoinType(value: 'outer_join');
  }

  public function __toString(): string
  {
    return $this->value;
  }
}

?>