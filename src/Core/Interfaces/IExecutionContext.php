<?php

namespace Assegai\Core\Interfaces;

/**
 * Interface describing details about the current request pipeline.
 */
interface IExecutionContext
{
  public function getClass(): object|string|false;

  public function getHandler(): callable|string|false;
}