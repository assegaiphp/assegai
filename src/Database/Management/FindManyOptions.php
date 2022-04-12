<?php

namespace Assegai\Database\Management;

class FindManyOptions extends FindOneOptions
{
  public function __construct(
    public readonly ?int $skip = null,
    public readonly ?int $limit = null
  ) { }

  public function __toString(): string
  {
    return "LIMIT {$this->limit} OFFSET {$this->skip}";
  }
}