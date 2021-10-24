<?php

namespace LifeRaft\Modules\Tests;

use LifeRaft\Core\BaseModule;
use LifeRaft\Core\Attributes\Module;

#[Module(
  controllers: [TestsController::class],
  exports: [],
  imports: [],
  providers: [TestsService::class, TestsRepository::class],
)]
class TestsModule extends BaseModule
{
}

?>