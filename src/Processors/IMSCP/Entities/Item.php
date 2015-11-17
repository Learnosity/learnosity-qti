<?php

namespace LearnosityQti\Processors\IMSCP\Entities;

class Item
{
    private $identifier;
    private $isvisible;
    private $identifierref;
    private $title;

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    public function getIsvisible()
    {
        return $this->isvisible;
    }

    public function setIsvisible($isvisible)
    {
        $this->isvisible = $isvisible;
    }

    public function getIdentifierref()
    {
        return $this->identifierref;
    }

    public function setIdentifierref($identifierref)
    {
        $this->identifierref = $identifierref;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }
}
