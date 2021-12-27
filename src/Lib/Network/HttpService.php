<?php

namespace Assegai\Lib\Network;

use Assegai\Core\Attributes\Injectable;
use Assegai\Core\Interfaces\IService;
use Assegai\Core\RequestMethod;

#[Injectable]
final class HttpService implements IService
{
  private ?string $id;
  protected static ?HttpService $instance = null;

  public function __construct(
    protected ?HttpRequestOptions $options = null
  )
  {
    $this->id = uniqid(prefix: 'http-');

    if (is_null(HttpService::$instance) || HttpService::$instance->id() !== $this->id)
    {
      HttpService::$instance = $this;
    }
  }

  public function id(): int
  {
    return $this->id;
  }

  public function request(
    string $method,
    string $url,
    ?HttpRequestOptions $options = null
  ): HttpResponse
  {
    # Create curl resource
    $ch = curl_init();

    # Set URL
    curl_setopt($ch, CURLOPT_URL, $url);

    if (is_null($options))
    {
      $options = $this->options;
    }

    if (!is_null($options) && !is_null($options->headers()))
    {
      foreach ($options->headers() as $header)
      {
        curl_setopt(handle: $ch, option: CURLOPT_HEADER, value: $header);
      }
    }

    switch($method)
    {
      case RequestMethod::DELETE:
        curl_setopt(handle: $ch, option: CURLOPT_CUSTOMREQUEST, value: 'DELETE');
        break;

      case RequestMethod::HEAD:
        curl_setopt(handle: $ch, option: CURLOPT_CUSTOMREQUEST, value: 'HEAD');
        break;

      case RequestMethod::PATCH:
        curl_setopt(handle: $ch, option: CURLOPT_CUSTOMREQUEST, value: 'PATCH');
        break;

      case RequestMethod::POST:
        curl_setopt(handle: $ch, option: CURLOPT_CUSTOMREQUEST, value: 'POST');
        break;

      case RequestMethod::PUT:
        curl_setopt(handle: $ch, option: CURLOPT_CUSTOMREQUEST, value: 'PUT');
        break;

      default:
        curl_setopt(handle: $ch, option: CURLOPT_CUSTOMREQUEST, value: 'GET');
    }

    # Return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    # $output contains the output string
    $output = curl_exec($ch);

    $isOk = is_bool($output) ? $output : true;

    $errors = $isOk ? [] : [ 'message' => curl_error(handle: $ch) ];

    # Close curl resource to free up system resources
    curl_close($ch);

    return new HttpResponse( value: $output, errors: $errors, isOK: $isOk );
  }

  public function delete(
    string $url,
    ?HttpRequestOptions $options = null
  ): HttpResponse
  {
    return $this->request(method: RequestMethod::GET, url: $url, options: $options);
  }

  public function get(
    string $url,
    ?HttpRequestOptions $options = null
  ): HttpResponse
  {
    return $this->request(method: RequestMethod::GET, url: $url, options: $options);
  }

  public function head(
    string $url,
    ?HttpRequestOptions $options = null
  ): HttpResponse
  {
    return $this->request(method: RequestMethod::HEAD, url: $url, options: $options);
  }

  public function patch(
    string $url,
    ?HttpRequestOptions $options = null
  ): HttpResponse
  {
    return $this->request(method: RequestMethod::PATCH, url: $url, options: $options);
  }

  public function post(
    string $url,
    ?HttpRequestOptions $options = null
  ): HttpResponse
  {
    return $this->request(method: RequestMethod::POST, url: $url, options: $options);
  }

  public function put(
    string $url,
    ?HttpRequestOptions $options = null
  ): HttpResponse
  {
    return $this->request(method: RequestMethod::PUT, url: $url, options: $options);
  }

  public static function instance(): ?IService
  {
    return HttpService::$instance;
  }
}

