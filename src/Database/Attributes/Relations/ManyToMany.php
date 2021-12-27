<?php

namespace Assegai\Database\Attributes\Relations;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ManyToMany
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
  }
}
