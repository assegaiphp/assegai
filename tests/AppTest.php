<?php

declare(strict_types=1);

use LifeRaft\Core\App;
use LifeRaft\Core\Interfaces\IModule;
use LifeRaft\Core\Request;
use PHPUnit\Framework\TestCase;

final class AppTest extends TestCase
{
  public function testCreateWithParams(): void
  {
    $request = new Request();
    $config = require_once('app/config/default.php');

    $this->assertInstanceOf(App::class, new App( request: $request, config: $config ));
  }

  public function testCreateWithEmptyConfig(): void
  {
    $request = new Request();
    $config = [];

    $this->assertInstanceOf(App::class, new App( request: $request, config: $config ));
  }

  public function testReadConfigArray(): void
  {
    $app = new App( request: new Request, config: [] );
    $this->assertClassHasAttribute(attributeName: 'config', className: App::class);
    $this->assertIsArray($app->config());
  }

  public function testReadRequestObject(): void
  {
    $app = new App( request: new Request, config: [] );
    $this->assertClassHasAttribute(attributeName: 'request', className: App::class);
    $this->assertInstanceOf(Request::class, $app->request(), 'Cannot read the request object member variable.');
  }
}

?>