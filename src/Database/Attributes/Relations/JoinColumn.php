<?php

namespace Assegai\Database\Attributes\Relations;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class JoinColumn
{
  public function __construct(public null | JoinColumnOptions | array $options = NULL)
  {
    if (!isset($this->options))
    {
      $this->options = new JoinColumnOptions();
    }
  }
}