<?php

namespace Assegai\Core;

use Assegai\Core\Responses\Response;

class Debugger
{
  /**
   * Logs 
   */
  public static function log(...$message): void
  {
    echo "<pre>";
    foreach ($message as $logMessage)
    {
      var_export( match( gettype($logMessage) ) {
        'string' => $logMessage, 
        'array' => implode( '<br>', $logMessage ), 
        'object' => json_encode( $logMessage ), 
      });
    }
    echo "</pre>";
  }

  /**
   * @param mixed ...$message
   * 
   * @deprecated 1.0.0 No longer used by internal code and not recommended.
   * @see Debugger::logError 
   */
  public static function log_error(...$message): void
  {
    call_user_func_array([self::class, 'logError'], $message);
  }

  /**
   * @param mixed ...$message
   */
  public static function logError(...$message): void
  {
    foreach ($message as $logMessage)
    {
      error_log( match( gettype($logMessage) ) {
        'string' => $logMessage, 
        'array' => implode( '\n', $logMessage ), 
        'object' => json_encode( $logMessage ), 
      });
    }
  }

  public static function logWarning(...$message): void
  {

  }

  public static function print(...$args): void
  {
    echo "<pre>";
    foreach ($args as $message)
    {
      echo $message . "<br>";
    }
    echo "</pre>";
  }

  public static function respond(Response $response): void
  {
    if (Config::environment('ENVIRONMENT') === 'DEV')
    {
      exit($response);
    }
  }

  /**
   * @param bool $return
   * 
   * @deprecated 1.0.0 No longer used by internal code and not recommended.
   */
  public static function print_json_error(bool $return = false): ?string
  {
    return self::printJSONError(return: $return);
  }

  /**
   * @param bool $return If you would like to capture the output of printJSONError(), 
   * use the return parameter. When this parameter is set to true, printJSONError() 
   * will return the information rather than print it.
   * 
   * @return null|string Returns the error information if the $return parameter is 
   * set to true, otherwise null.
   */
  public static function printJSONError(bool $return = false): ?string
  {
    $message = match (json_last_error()) {
      JSON_ERROR_NONE           => ' - No errors',
      JSON_ERROR_DEPTH          => ' - Maximum stack depth exceeded',
      JSON_ERROR_STATE_MISMATCH => ' - Underflow or the modes mismatch',
      JSON_ERROR_CTRL_CHAR      => ' - Unexpected control character found',
      JSON_ERROR_SYNTAX         => ' - Syntax error, malformed JSON',
      JSON_ERROR_UTF8           => ' - Malformed UTF-8 characters, possibly incorrectly encoded',
      default                   => ' - Unknown error',
    };
    $message .= "\n";

    if ($return)
    {
      return $message;
    }
    else
    {
      echo $message;
    }

    return null;
  }
}
