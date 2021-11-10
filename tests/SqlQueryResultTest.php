<?php
declare(strict_types=1);

use Assegai\Database\Queries\SQLQueryResult;
use PHPUnit\Framework\TestCase;

final class SqlQueryResultTest extends TestCase
{
  const DATA = [
      ['id' => 1, 'username' => 'user01'],
      ['id' => 1, 'username' => 'user01'],
      ['id' => 1, 'username' => 'user01'],
      ['id' => 1, 'username' => 'user01'],
      ['id' => 1, 'username' => 'user01'],
  ];
  const ERRORS = [
    ['code' => 1002, 'symbol' => 'ER_NO', 'SQLSTATE' => 'HY000', 'message' => 'NO'],
    ['code' => 1003, 'symbol' => 'ER_YES', 'SQLSTATE' => 'HY000', 'message' => 'YES']
  ];

  public function testCreateInstance(): void
  {
    $result = new SQLQueryResult(data: $this::DATA);
    $this->assertInstanceOf(SqlQueryResult::class, $result);
  }

  public function testGetIsokProperty(): void
  {
    $result = new SqlQueryResult(data: $this::DATA, isOK: true);
    $this->assertClassHasAttribute('isOK', SqlQueryResult::class);
    $this->assertIsBool($result->isOK());
  }

  public function testGetIserrorProperty(): void
  {
    $result = new SqlQueryResult(data: $this::DATA, isOK: false);
    $this->assertClassHasAttribute('isOK', SqlQueryResult::class);
    $this->assertIsBool($result->isError());
  }

  public function testResultValueEqualsDataArrayIfIsokIsTrue(): void
  {
    $result = new SqlQueryResult(data: $this::DATA, errors: $this::ERRORS, isOK: true);
    $this->assertClassHasAttribute('data', SqlQueryResult::class);
    $this->assertContains('value', get_class_methods($result));
    $this->assertEquals($this::DATA, $result->value());
  }

  public function testResultValueEqualsErrorsArrayIfIsokIsFalse(): void
  {
    $result = new SqlQueryResult(data: $this::DATA, errors: $this::ERRORS, isOK: false);
    $this->assertClassHasAttribute('data', SqlQueryResult::class);
    $this->assertContains('value', get_class_methods($result));
    $this->assertEquals($this::ERRORS, $result->value());
  }

  public function testValidateContainsMethod(): void
  {
    $result = new SqlQueryResult(data: [], isOK: true);
    $this->assertTrue($result->isOK());
    $this->assertContains('contains', get_class_methods($result));
    $this->assertIsBool($result->contains(['id' => 1]));
  }

  public function testValidateContainsErrorMethod(): void
  {
    $result = new SqlQueryResult(data: [], isOK: false);
    $this->assertTrue($result->isError());
    $this->assertContains('containsError', get_class_methods($result));
    $this->assertIsBool($result->contains(['code' => 1002]));
  }

  public function testConvertToArray(): void
  {
    $result = new SqlQueryResult(data: [], isOK: true);
    $this->assertIsArray($result->toArray());
  }

  public function testConvertToJson(): void
  {
    $result = new SqlQueryResult(data: [], isOK: true);
    $this->assertJson($result->toJSON());
  }

  public function testConvertToString(): void
  {
    $result = new SqlQueryResult(data: [], isOK: true);
    $this->assertIsString(strval($result));
  }
}

?>