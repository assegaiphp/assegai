<?php

namespace LifeRaft\Core;

class HttpStatusCode
{
  public function __construct(
    private int $code,
    private string $name,
    private string $description,
  ) { }

  public function __toString(): string
  {
    return "{$this->code} - {$this->name}";
  }

  public function code(): int
  {
    return $this->code;
  }

  public function name(): string
  {
    return $this->name;
  }

  public function description(): string
  {
    return $this->description;
  }
}

class HttpStatus
{
  public static function Continue(): HttpStatusCode
  {
    return new HttpStatusCode(
      code: 100,
      name: 'Continue',
      description: 'This interim response indicates that everything so far is OK and that the client should continue the request, or ignore the response if the request is already finished.'
    );
  }

  /**
   * The request has succeeded. The meaning of the success depends on the HTTP method:
   * 
   * - `GET`: The resource has been fetched and is transmitted in the message body.
   * - `HEAD`: The representation headers are included in the response without any message body.
   * - `PUT` or `POST`: The resource describing the result of the action is transmitted in the message body.
   * - `TRACE`: The message body contains the request message as received by the server.
   */
  public static function OK(): HttpStatusCode
  {
    return new HttpStatusCode(
      code: 200,
      name: 'OK',
      description: 'The request has succeeded. The meaning of the success depends on the HTTP method.'
    );
  }

  /**
   * The request has succeeded and a new resource has been 
   * created as a result. This is typically the response sent 
   * after `POST` requests, or some `PUT` requests.
   * 
   * @return HttpStatusCode Returns an HttpStatusCode object.
   */
  public static function Created(): HttpStatusCode {
    return new HttpStatusCode(
      code: 201,
      name: 'Created',
      description: 'The request has succeeded and a new resource has been created as a result. This is typically the response sent after POST requests, or some PUT requests.'
    );
  }

  /**
   * The request has been received but not yet acted upon. It is 
   * noncommittal, since there is no way in HTTP to later send an 
   * asynchronous response indicating the outcome of the request. 
   * It is intended for cases where another process or server 
   * handles the request, or for batch processing.
   * 
   * @return HttpStatusCode Returns an HttpStatusCode object.
   */
  public static function Accepted(): HttpStatusCode {
    return new HttpStatusCode(
      code: 202,
      name: 'Accepted',
      description: 'The request has succeeded and a new resource has been created as a result. This is typically the response sent after POST requests, or some PUT requests.'
    );
  }

  /**
   * There is no content to send for this request, but the headers may 
   * be useful. The user-agent may update its cached headers for this 
   * resource with the new ones.
   */
  public static function NoContent(): HttpStatusCode {
    return new HttpStatusCode(
      code: 204,
      name: 'No Content',
      description: 'There is no content to send for this request, but the headers may be useful. The user-agent may update its cached headers for this resource with the new ones.'
    );
  }

  /**
   * The server could not understand the request due to invalid syntax.
   */
  public static function BadRequest(): HttpStatusCode {
    return new HttpStatusCode(
      code: 400,
      name: 'Bad Request',
      description: 'The server could not understand the request due to invalid syntax.'
    );
  }

  /**
   * Although the HTTP standard specifies "unauthorized", semantically 
   * this response means "unauthenticated". That is, the client must 
   * authenticate itself to get the requested response.
   */
  public static function Unauthorized(): HttpStatusCode {
    return new HttpStatusCode(
      code: 401,
      name: 'Unauthorized',
      description: 'Although the HTTP standard specifies "unauthorized", semantically this response means "unauthenticated". That is, the client must authenticate itself to get the requested response.'
    );
  }

  /**
   * The client does not have access rights to the content; that is, it is 
   * unauthorized, so the server is refusing to give the requested 
   * resource. Unlike 401, the client's identity is known to the server.
   */
  public static function Forbidden(): HttpStatusCode {
    return new HttpStatusCode(
      code: 403,
      name: 'Forbidden',
      description: 'The client does not have access rights to the content; that is, it is unauthorized, so the server is refusing to give the requested resource. Unlike 401, the client\'s identity is known to the server.'
    );
  }

  /**
   * The server can not find the requested resource. In the browser, this 
   * means the URL is not recognized. In an API, this can also mean that 
   * the endpoint is valid but the resource itself does not exist. Servers 
   * may also send this response instead of 403 to hide the existence of a 
   * resource from an unauthorized client. This response code is probably 
   * the most famous one due to its frequent occurrence on the web.
   */
  public static function NotFound(): HttpStatusCode {
    return new HttpStatusCode(
      code: 404,
      name: 'Not Found',
      description: 'The server can not find the requested resource. In the browser, this means the URL is not recognized. In an API, this can also mean that the endpoint is valid but the resource itself does not exist. Servers may also send this response instead of 403 to hide the existence of a resource from an unauthorized client. This response code is probably the most famous one due to its frequent occurrence on the web.'
    );
  }

  /**
   * The request method is known by the server but is not supported by the 
   * target resource. For example, an API may forbid DELETE-ing a resource.
   */
  public static function MethodNotAllowed(): HttpStatusCode {
    return new HttpStatusCode(
      code: 405,
      name: 'Method Not Allowed',
      description: 'The request method is known by the server but is not supported by the target resource. For example, an API may forbid DELETE-ing a resource.'
    );
  }

  /**
   * This response is sent when a request conflicts with the current state of the server.
   */
  public static function Conflict(): HttpStatusCode {
    return new HttpStatusCode(
      code: 409,
      name: 'Conflict',
      description: 'This response is sent when a request conflicts with the current state of the server.'
    );
  }

  /**
   * The server has encountered a situation it doesn't know how to handle.
   */
  public static function InternalServerError(): HttpStatusCode {
    return new HttpStatusCode(
      code: 500,
      name: 'Internal Server Error',
      description: 'The server has encountered a situation it doesn\'t know how to handle.'
    );
  }

  /**
   * The request method is not supported by the server and cannot be handled. 
   * The only methods that servers are required to support (and therefore 
   * that must not return this code) are GET and HEAD.
   */
  public static function NotImplemented(): HttpStatusCode {
    return new HttpStatusCode(
      code: 501,
      name: 'Not Implemented',
      description: 'The request method is not supported by the server and cannot be handled. The only methods that servers are required to support (and therefore that must not return this code) are GET and HEAD.'
    );
  }

  /**
   * The server is not ready to handle the request. Common causes are a 
   * server that is down for maintenance or that is overloaded. Note 
   * that together with this response, a user-friendly page explaining 
   * the problem should be sent. This response should be used for temporary 
   * conditions and the Retry-After: HTTP header should, if possible, 
   * contain the estimated time before the recovery of the service. The 
   * webmaster must also take care about the caching-related headers that 
   * are sent along with this response, as these temporary condition 
   * responses should usually not be cached.
   */
  public static function ServiceUnavailable(): HttpStatusCode {
    return new HttpStatusCode(
      code: 503,
      name: 'Service Unavailable',
      description: 'The server is not ready to handle the request. Common causes are a server that is down for maintenance or that is overloaded. Note that together with this response, a user-friendly page explaining the problem should be sent. This response should be used for temporary conditions and the Retry-After: HTTP header should, if possible, contain the estimated time before the recovery of the service. The webmaster must also take care about the caching-related headers that are sent along with this response, as these temporary condition responses should usually not be cached.'
    );
  }
}

?>