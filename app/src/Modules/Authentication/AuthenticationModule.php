<?php

namespace Assegai\Modules\Authentication;

use Assegai\Core\BaseModule;
use Assegai\Core\Attributes\Module;

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