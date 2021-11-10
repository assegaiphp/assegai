<?php

namespace Assegai\Modules\Home;

use Assegai\Core\Attributes\Module;
use Assegai\Core\BaseModule;
use Assegai\Modules\Home\HomeService;

#[Module(
  controllers: [HomeController::class],
  providers: [HomeService::class]
)]
class HomeModule extends BaseModule
{
}

?>