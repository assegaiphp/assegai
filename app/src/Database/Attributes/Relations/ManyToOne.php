<?php

namespace LifeRaft\Database\Attributes\Relations;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ManyToOne
{
  public function __construct(
    public ?RelationsOptions $options = null
  ) {
    if (is_null($this->options))
    {
      $this->options = new RelationsOptions();
    }
  }
}
