<?php

declare(strict_types=1);

use Assegai\Core\App;
use Assegai\Core\Request;
use Assegai\Core\Routing\Router;
use PHPUnit\Framework\TestCase;

final class RequestTest extends TestCase
{
  public function testCreateInstanceWithNoParams(): void
  {
    $this->assertInstanceOf(Request::class, new Request, 'Cannot create request instance.');
  }

  public function testGetAppReference(): void
  {
    $request = new Request;
    $app = new App( request: $request, router: new Router, config: require('app/config/default.php') );

    $this->assertInstanceOf(App::class, $request->app(), 'Can get app reference');
  }

  public function testGetHeaders(): void
  {
    $request = new Request;
    $_SERVER['HTTP_HOST'] = 'localhost';
    $this->assertIsString($request->header('host'));
    $this->assertIsArray($request->allHeaders());
  }

  public function testGetUri(): void
  {
    $request = new Request;
    $this->assertIsString($request->uri());
  }

  public function testConvertToArray(): void
  {
    $request = new Request;
    $app = new App( request: $request, router: new Router, config: require('app/config/default.php') );

    $this->assertIsArray($request->toArray(), 'Can convert to array');
  }

  public function testConvertToJson(): void
  {
    $request = new Request;
    $app = new App( request: $request, router: new Router, config: require('app/config/default.php') );

    $this->assertJson($request->toJSON(), 'Can convert to array');
  }

  public function testConvertToString(): void
  {
    $request = new Request;
    $app = new App( request: $request, router: new Router, config: require('app/config/default.php') );

    $this->assertIsString(strval($request), 'Can convert to array');
  }

}

?>