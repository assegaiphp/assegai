<?php

use LifeRaft\Modules\Home\HomeModule;
use LifeRaft\Modules\Users\UsersModule;

return [
  '/' => HomeModule::class,
  'authentication' => HomeModule::class,
  'trackings' => HomeModule::class,
  'queries' => HomeModule::class,
  'people' => HomeModule::class,
  'subjects' => HomeModule::class,
  'followings' => HomeModule::class,
  'users' => UsersModule::class,
];

?>