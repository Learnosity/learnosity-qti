<?php

namespace LearnosityQti\Processors\IMSCP\Entities;

class Organization
{
    private $identifier;
    private $title;
    private $structure;
    private $items;

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getStructure()
    {
        return $this->structure;
    }

    public function setStructure($structure)
    {
        $this->structure = $structure;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setItems($items)
    {
        $this->items = $items;
    }
}
