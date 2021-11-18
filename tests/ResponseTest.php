<?php
declare(strict_types=1);

use Assegai\Core\App;
use Assegai\Core\Request;
use Assegai\Core\Responses\HttpStatus;
use Assegai\Core\Responses\HttpStatusCode;
use Assegai\Core\Responses\Response;
use Assegai\Core\Responses\ResponseType;
use Assegai\Core\Routing\Router;
use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{
  public function testCreateInstanceWithNoParams(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App( request: $request, router: new Router, config: require('app/config/default.php') );
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertInstanceOf(Response::class, new Response());
  }

  public function testGetPropertyNamedTotalOfTypeInt(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App(request: $request, router: new Router, config: require('app/config/default.php'));
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertClassHasAttribute('total', Response::class);
    $this->assertIsInt((new Response())->total());
  }

  public function testGetPropertyNamedLimitOfTypeInt(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App(request: $request, router: new Router, config: require('app/config/default.php'));
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertClassHasAttribute('limit', Response::class);
    $this->assertIsInt((new Response())->limit());
  }

  public function testGetPropertyNamedSkipOfTypeInt(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App(request: $request, router: new Router, config: require('app/config/default.php'));
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertClassHasAttribute('skip', Response::class);
    $this->assertIsInt((new Response())->skip());
  }

  public function testGetNonNullPropertyNamedData(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App(request: $request, router: new Router, config: require('app/config/default.php'));
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertClassHasAttribute('data', Response::class);
    $this->assertNotNull((new Response())->data());
  }

  public function testGetPropertyNamedStatusOfTypeHttpstatuscode(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App(request: $request, router: new Router, config: require('app/config/default.php'));
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertClassHasAttribute('status', Response::class);
    $this->assertInstanceOf(HttpStatusCode::class, (new Response( status: HttpStatus::OK() ))->status());
  }

  public function testGetPropertyNamedStatusOfTypeType(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App(request: $request, router: new Router, config: require('app/config/default.php'));
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertClassHasAttribute('type', Response::class);
    $this->assertInstanceOf(ResponseType::class, (new Response())->type());
  }

  public function testConvertToArray(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App(request: $request, router: new Router, config: require('app/config/default.php'));
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertIsArray((new Response)->toArray());
  }

  public function testConvertToJson(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App(request: $request, router: new Router, config: require('app/config/default.php'));
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertJson((new Response)->toJSON());
  }
}

?>