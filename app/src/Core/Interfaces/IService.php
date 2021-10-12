<?php

namespace LifeRaft\Core\Interfaces;

interface IService
{
  public static function instance(): IService|null;
}

?>