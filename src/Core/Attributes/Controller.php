<?php

namespace Assegai\Core\Attributes;

use Attribute;
use Assegai\Core\RequestMethod;
use Assegai\Core\Responses\HttpStatusCode;

#[Attribute(Attribute::TARGET_CLASS)]
final class Controller
{
  public function __construct(
    public string $path = '/',
    public ?string $host = null,
    public ?HttpStatusCode $status = null,
    public ?array $forbiddenMethods = [RequestMethod::DELETE],
  ) { }
}

