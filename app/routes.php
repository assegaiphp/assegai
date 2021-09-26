<?php

use LifeRaft\Modules\Home;
use LifeRaft\Modules\Queries;
use LifeRaft\Modules\Users;

return [
  '/' => Home\HomeController::class,
  'authentication' => Queries\QueriesController::class,
  'trackings' => Queries\QueriesController::class,
  'queries' => Queries\QueriesController::class,
  'people' => Queries\QueriesController::class,
  'subjects' => Queries\QueriesController::class,
  'followings' => Queries\QueriesController::class,
  'users' => Users\UsersController::class,
];

?>