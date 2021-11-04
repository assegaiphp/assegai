<?php

namespace LifeRaft\Modules\Authentication;

use LifeRaft\Core\BaseModule;
use LifeRaft\Core\Attributes\Module;

#[Module(
  controllers: [AuthenticationController::class],
  exports: [],
  imports: [],
  providers: [AuthenticationService::class],
)]
class AuthenticationModule extends BaseModule
{
}

?>