<?php

use LifeRaft\Modules\Home;
use LifeRaft\Modules\Queries;
use LifeRaft\Modules\Users;
use LifeRaft\Modules\Trackings;

return [
  '/' => Home\HomeModule::class,
  'authentication' => Home\HomeModule::class,
  'trackings' => Home\HomeModule::class,
  'queries' => Home\HomeModule::class,
  'people' => Home\HomeModule::class,
  'subjects' => Home\HomeModule::class,
  'followings' => Home\HomeModule::class,
  'users' => Home\HomeModule::class,
];

?>