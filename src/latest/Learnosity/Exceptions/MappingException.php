<?php

namespace Learnosity\Exceptions;

class MappingException extends \Exception
{
    const WARNING = 'warning';
    const CRITICAL = 'critical';

    public function __construct($message, $type = self::WARNING, $previous = null)
    {
        $this->type = $type;
        parent::__construct($message, 0, $previous);
    }

    public function getType()
    {
        return $this->type;
    }

    public function __toString()
    {
        return "[" . $this->type . "]" . $this->getMessage();
    }
}
