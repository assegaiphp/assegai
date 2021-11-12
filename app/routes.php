<?php

use Assegai\Core\Routing\Route;
use Assegai\Modules\Home\HomeModule;
use Assegai\Modules\Tests\TestsModule;
use Assegai\Modules\Users\UsersModule;
use Assegai\Modules\Authentication\AuthenticationModule;

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

