<?php

use LifeRaft\Core\Routing\Route;
use LifeRaft\Modules\Home\HomeModule;
use LifeRaft\Modules\Tests\TestsModule;
use LifeRaft\Modules\Users\UsersModule;
use LifeRaft\Modules\Authentication\AuthenticationModule;

return [
  new Route(path: '/', module: HomeModule::class),
  new Route(path: 'trackings', module: HomeModule::class),
  new Route(path: 'queries', module: HomeModule::class),
  new Route(path: 'people', module: HomeModule::class),
  new Route(path: 'subjects', module: HomeModule::class),
  new Route(path: 'tests', module: TestsModule::class),
  new Route(path: 'users', module: UsersModule::class),
  new Route(path: 'authentication', module: AuthenticationModule::class),
];

?>