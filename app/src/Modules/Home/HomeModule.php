<?php

namespace LifeRaft\Modules\Home;

use LifeRaft\Core\Attributes\Module;
use LifeRaft\Core\BaseModule;
use LifeRaft\Modules\Home\HomeService;

#[Module(
  controllers: [HomeController::class],
  providers: [HomeService::class]
)]
class HomeModule extends BaseModule
{
}

?>