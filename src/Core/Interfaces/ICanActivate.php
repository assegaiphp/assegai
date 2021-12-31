<?php

namespace Assegai\Core\Interfaces;

use Assegai\Core\ExecutionContext;

interface ICanActivate
{
  public function canActivate(ExecutionContext $context): bool;

  public function canDeactivate(ExecutionContext $context): bool;
}

