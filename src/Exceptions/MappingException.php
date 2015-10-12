<?php

namespace Learnosity\Exceptions;

class MappingException extends BaseKnownException
{
    public function __construct($message, $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
