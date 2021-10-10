<?php

use LifeRaft\Modules\Home;
use LifeRaft\Modules\Queries;
use LifeRaft\Modules\Users;
use LifeRaft\Modules\Trackings;

return [
  '/' => Home\HomeModule::class,
  'authentication' => Queries\HomeModule::class,
  'trackings' => Trackings\HomeModule::class,
  'queries' => Queries\HomeModule::class,
  'people' => Queries\HomeModule::class,
  'subjects' => Queries\HomeModule::class,
  'followings' => Queries\HomeModule::class,
  'users' => Users\HomeModule::class,
];

?>