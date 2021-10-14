<?php

declare(strict_types=1);

use LifeRaft\Core\App;
use LifeRaft\Core\Request;
use PHPUnit\Framework\TestCase;

final class RequestTest extends TestCase
{
  public function testCreateWithNoConstructorParams(): void
  {
    $this->assertInstanceOf(Request::class, new Request, 'Cannot create request instance.');
  }

  public function testReadAppReference(): void
  {
    $request = new Request;
    $app = new App( request: $request, config: require('app/config/default.php') );

    $this->assertInstanceOf(App::class, $request->app(), 'Can get app reference');
  }

  public function testConvertToArray(): void
  {
    $request = new Request;
    $app = new App( request: $request, config: require('app/config/default.php') );

    $this->assertIsArray($request->to_array(), 'Can convert to array');
  }

  public function testConvertToJson(): void
  {
    $request = new Request;
    $app = new App( request: $request, config: require('app/config/default.php') );

    $this->assertJson($request->to_json(), 'Can convert to array');
  }

  public function testConvertToString(): void
  {
    $request = new Request;
    $app = new App( request: $request, config: require('app/config/default.php') );

    $this->assertIsString(strval($request), 'Can convert to array');
  }

  public function testReadHeaders(): void
  {
    $request = new Request;
    $_SERVER['HTTP_HOST'] = 'localhost';
    $this->assertIsString($request->header('host'));
    $this->assertIsArray($request->all_headers());
  }

  public function testReadUri(): void
  {
    $request = new Request;
    $this->assertIsString($request->uri());
  }
}

?>