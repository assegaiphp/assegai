<?php

namespace Assegai\Database\Attributes\Relations;

use Attribute;

/**
 * The `JoinTable` attribute is used in many-to-many relationship to specify the owner side of the relationship.
 * Its also used to set a custom junction table's name, column names and referenced columns.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class JoinTable
{
  public function __construct(
    public ?JoinTableOptions $options = null
  ) { }
}