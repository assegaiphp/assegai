<?php

namespace LifeRaft\Core\Attributes;

use Attribute;
use LifeRaft\Core\Responses\HttpStatusCode;

#[Attribute(Attribute::TARGET_CLASS)]
final class Controller
{
  public function __construct(
    public string $path = '/',
    public ?string $host = null,
    public ?HttpStatusCode $status = null,
    public ?array $forbiddenMethods = [Patch::class],
  ) { }
}

?>