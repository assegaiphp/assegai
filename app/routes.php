<?php

use LifeRaft\Modules\Home\HomeModule;
use LifeRaft\Modules\Tests\TestsModule;
use LifeRaft\Modules\Users\UsersModule;
use LifeRaft\Modules\Authentication\AuthenticationModule;

return [
  '/' => HomeModule::class,
  'authentication' => HomeModule::class,
  'trackings' => HomeModule::class,
  'queries' => HomeModule::class,
  'people' => HomeModule::class,
  'subjects' => HomeModule::class,
  'tests' => TestsModule::class,
  'users' => UsersModule::class,
  'authentication' => AuthenticationModule::class,
];

?>