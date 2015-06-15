<?php

namespace Learnosity\Entities;

class Activity
{
    private $reference;
    private $data;

    function __construct($reference, $itemReferences)
    {
        $this->reference = $reference;
        $this->data['items'] = $itemReferences;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setItemReferences(array $references)
    {
        $this->data['items'] = $references;
    }

    public function getItemReferences()
    {
        return isset($this->data['items']) ?  $this->data['items'] : [];
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function setReference($reference)
    {
        $this->reference = $reference;
    }
}
