<?php

namespace Learnosity\Processors\IMSCP\Entities;

class Dependency
{
    protected $identifierref;

    /**
     * @return mixed
     */
    public function getIdentifierref()
    {
        return $this->identifierref;
    }

    /**
     * @param mixed $identifierref
     */
    public function setIdentifierref($identifierref)
    {
        $this->identifierref = $identifierref;
    }
}
