<?php

use LifeRaft\Modules\Home\HomeModule;
use LifeRaft\Modules\Tests\TestsModule;
use LifeRaft\Modules\Users\UsersModule;

return [
  '/' => HomeModule::class,
  'authentication' => HomeModule::class,
  'trackings' => HomeModule::class,
  'queries' => HomeModule::class,
  'people' => HomeModule::class,
  'subjects' => HomeModule::class,
  'tests' => TestsModule::class,
  'followings' => HomeModule::class,
  'users' => UsersModule::class,
];

?>