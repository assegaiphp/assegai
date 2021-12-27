<?php

namespace Assegai\Core\Interfaces;

interface IService
{
  public static function instance(): IService|null;
}

