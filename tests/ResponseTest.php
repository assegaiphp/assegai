<?php
declare(strict_types=1);

use LifeRaft\Core\App;
use LifeRaft\Core\HttpStatusCode;
use LifeRaft\Core\Request;
use LifeRaft\Core\Response;
use LifeRaft\Core\ResponseType;
use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{
  public function testCreateInstanceWithNoParams(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App( request: $request, config: require('app/config/default.php') );
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertInstanceOf(Response::class, new Response());
  }

  public function testReadMemberVariableNamedTotalOfTypeInt(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App(request: $request, config: require('app/config/default.php'));
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertClassHasAttribute('total', Response::class);
    $this->assertIsInt((new Response())->total());
  }

  public function testReadMemberVariableNamedLimitOfTypeInt(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App(request: $request, config: require('app/config/default.php'));
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertClassHasAttribute('limit', Response::class);
    $this->assertIsInt((new Response())->limit());
  }

  public function testReadMemberVariableNamedSkipOfTypeInt(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App(request: $request, config: require('app/config/default.php'));
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertClassHasAttribute('skip', Response::class);
    $this->assertIsInt((new Response())->skip());
  }

  public function testReadNonNullMemberVariableNamedData(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App(request: $request, config: require('app/config/default.php'));
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertClassHasAttribute('data', Response::class);
    $this->assertNotNull((new Response())->data());
  }

  public function testReadMemberVariableNamedStatusOfTypeHttpstatuscode(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App(request: $request, config: require('app/config/default.php'));
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertClassHasAttribute('status', Response::class);
    $this->assertInstanceOf(HttpStatusCode::class, (new Response())->status());
  }

  public function testReadMemberVariableNamedStatusOfTypeType(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App(request: $request, config: require('app/config/default.php'));
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertClassHasAttribute('type', Response::class);
    $this->assertInstanceOf(ResponseType::class, (new Response())->type());
  }

  public function testConvertToArray(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App(request: $request, config: require('app/config/default.php'));
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertIsArray((new Response)->to_array());
  }

  public function testConvertToJson(): void
  {
    $request = new Request;
    $GLOBALS['app'] = new App(request: $request, config: require('app/config/default.php'));
    $_GET = ['limit' => 100, 'skip' => 0];

    $this->assertJson((new Response)->to_json());
  }
}

?>