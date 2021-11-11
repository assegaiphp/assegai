<?php

use Assegai\Lib\Network\HttpService;
use PHPUnit\Framework\TestCase;

final class HttpServiceTest extends TestCase
{
  public function testGetRequest(): void {
    $http = new HttpService();
    $response = $http->get(url: 'https://jsonplaceholder.typicode.com/comments?postId=1');

    $this->assertTrue(condition: $response->isOK());
  }

  public function testPostRequest(): void {}

  public function testPutRequest(): void {}

  public function testPatchRequest(): void {}

  public function testDeleteRequest(): void {}
}

?>