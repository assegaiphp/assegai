<?php

namespace Assegai\Modules\Users;

use Assegai\Core\BaseModule;
use Assegai\Core\Attributes\Module;

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