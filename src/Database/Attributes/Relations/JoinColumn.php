<?php

namespace Assegai\Database\Attributes\Relations;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class JoinColumn
{
  public function __construct(
    public ?JoinColumnOptions $options = null
  )
  {
    if (is_null($this->options))
    {
      $this->options = new JoinColumnOptions();
    }
  }
}