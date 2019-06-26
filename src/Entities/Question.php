<?php

namespace LearnosityQti\Entities;

class Question extends BaseEntity
{
    private $reference;
    private $type;
    private $data;
    private $widget_type;
    private $item_reference;

    function __construct($type, $reference, BaseQuestionType $data, $itemReference = '')
    {
        $this->data          = $data;
        $this->reference     = $reference;
        $this->type          = $type;
        $this->widget_type   = $data->get_widget_type();
        $this->item_reference = $itemReference;
    }

    public function get_reference()
    {
        return $this->reference;
    }

    public function set_reference($reference)
    {
        $this->reference = $reference;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function set_type($type)
    {
        $this->type = $type;
    }

    public function get_data()
    {
        return $this->data;
    }

    public function set_data(BaseQuestionType $data)
    {
        $this->data = $data;
    }

    public function set_item_reference($itemReference)
    {
        $this->item_reference = $itemReference;
    }

    public function get_item_reference()
    {
        return $this->item_reference;
    }

    /**
     * @override
     **/
    public function to_array()
    {
        $question         = get_object_vars($this);
        $question['data'] = $this->data->to_array();
        return $question;
    }
}
