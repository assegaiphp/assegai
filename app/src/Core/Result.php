<?php

namespace LifeRaft\Core;

use \ArrayObject;

class Result extends ArrayObject
{
    public function __construct(
        protected array $data = []
    )
    {
        parent::__construct( array: $data );
    }
}

?>