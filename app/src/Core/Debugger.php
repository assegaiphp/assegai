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
        foreach ($message as $log_message)
        {
            var_export( match( gettype($log_message) ) {
               'string' => $log_message, 
               'array' => implode( '<br>', $log_message ), 
               'object' => json_encode( $log_message ), 
            });
        }
        echo "</pre>";
    }

    public static function log_error(...$message): void
    {
        foreach ($message as $log_message)
        {
            error_log( match( gettype($log_message) ) {
               'string' => $log_message, 
               'array' => implode( '\n', $log_message ), 
               'object' => json_encode( $log_message ), 
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