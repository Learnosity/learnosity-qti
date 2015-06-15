<?php

namespace Learnosity\Exceptions;

class MappingException extends \Exception
{

    const WARNING = 'warning';
    const CRITICAL = 'critical';

    public function __construct($message, $type = self::WARNING)
    {
        $this->type = $type;
        parent::__construct($message);

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