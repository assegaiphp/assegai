<?php

namespace Assegai\Core\Interfaces;

interface ICanActivate
{
  public function canActivate(mixed $context): bool;

  public function canDeactivate(mixed $context): bool;
}

?>