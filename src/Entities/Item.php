<?php

namespace Learnosity\Entities;

class Item extends BaseEntity
{
    protected $reference;
    protected $status;
    protected $content;
    protected $workflow;
    protected $metadata;
    protected $description;
    protected $questionReferences;

    function __construct($reference, $questionReferences, $content)
    {
        $this->content = $content;
        $this->questionReferences = $questionReferences;
        $this->reference = $reference;
    }

    public function get_content()
    {
        return $this->content;
    }

    public function set_content($content)
    {
        $this->content = $content;
    }

    public function get_description()
    {
        return $this->description;
    }

    public function set_description($description)
    {
        $this->description = $description;
    }

    public function get_metadata()
    {
        return $this->metadata;
    }

    public function set_metadata($metadata)
    {
        $this->metadata = $metadata;
    }

    public function get_questionReferences()
    {
        return $this->questionReferences;
    }

    public function set_questionReferences($questionReferences)
    {
        $this->questionReferences = $questionReferences;
    }

    public function get_reference()
    {
        return $this->reference;
    }

    public function set_reference($reference)
    {
        $this->reference = $reference;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($status)
    {
        $this->status = $status;
    }

    public function get_workflow()
    {
        return $this->workflow;
    }

    public function set_workflow($workflow)
    {
        $this->workflow = $workflow;
    }
}
