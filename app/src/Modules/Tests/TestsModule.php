<?php

namespace Assegai\Modules\Tests;

use Assegai\Core\BaseModule;
use Assegai\Core\Attributes\Module;

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