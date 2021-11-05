<?php

namespace LifeRaft\Modules\Users;

use LifeRaft\Core\BaseModule;
use LifeRaft\Core\Attributes\Module;

#[Module(
  controllers: [UsersController::class],
  exports: [],
  imports: [],
  providers: [UsersService::class, UsersRepository::class],
)]
class UsersModule extends BaseModule
{
}

?>