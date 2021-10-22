<?php

namespace LifeRaft\Core;

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

    public static function log_error(...$message): void
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

    public static function print(...$args): void
    {
        echo "<pre>";
        foreach ($args as $message)
        {
            echo $message . "<br>";
        }
        echo "</pre>";
    }
}

?>