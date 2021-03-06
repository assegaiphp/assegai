<?php

declare(strict_types=1);

use Assegai\Core\App;
use Assegai\Core\Request;
use Assegai\Core\Routing\Router;
use PHPUnit\Framework\TestCase;

final class AppTest extends TestCase
{
  public function testCreateInstanceWithParams(): void
  {
    $request = new Request();
    $config = require_once('app/config/default.php');
    $router = new Router();

    $this->assertInstanceOf(App::class, new App( request: $request, router: $router, config: $config ));
  }

  public function testCreateInstanceWithEmptyConfig(): void
  {
    $request = new Request();
    $config = [];
    $router = new Router();

    $this->assertInstanceOf(App::class, new App( request: $request, router: $router, config: $config ));
  }

  public function testGetConfigArray(): void
  {
    $app = new App( request: new Request, router: new Router, config: [] );
    $this->assertClassHasAttribute(attributeName: 'config', className: App::class);
    $this->assertIsArray($app->config());
  }

  public function testGetRequestObject(): void
  {
    $app = new App( request: new Request, router: new Router, config: [] );
    $this->assertClassHasAttribute(attributeName: 'request', className: App::class);
    $this->assertInstanceOf(Request::class, $app->request(), 'Cannot read the request object member variable.');
  }
}

?>