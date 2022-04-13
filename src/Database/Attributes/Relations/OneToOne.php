<?php

namespace Assegai\Database\Attributes\Relations;

use Assegai\Core\Exceptions\ClassNotFoundException;
use Attribute;

/**
 * One-to-one is a relation where A contains only one instance of B and B 
 * contains only one instance of A. For example, if we had `User` and 
 * `Profile` entities, `User` can have only a single `Profile`, while a 
 * single `Profile` is owned by one `User`.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class OneToOne
{
  public function __construct(
    public string $type,
    public ?string $name = null,
    public ?string $alias = null,
    public ?RelationsOptions $options = null
  ) {
    if (is_null($this->options))
    {
      $this->options = new RelationsOptions();
    }

    if (!class_exists($type))
    {
      throw new ClassNotFoundException(className: $type);
    }
  }
}

?>