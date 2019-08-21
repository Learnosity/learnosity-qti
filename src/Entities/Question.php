<?php

namespace LearnosityQti\Entities;

class Question extends BaseEntity
{
    private $reference;
    private $type;
    private $data;
    private $widget_type;
    private $item_reference;
    private $content;
    private $features;

    function __construct($type, $reference, BaseQuestionType $data, $itemReference = '', $content = '', $features = '')
    {
        $this->data           = $data;
        $this->reference      = $reference;
        $this->type           = $type;
        $this->widget_type    = $data->get_widget_type();
        $this->item_reference = $itemReference;
        $this->content        = $content;
        $this->features       = $features;
    }

    public function get_reference()
    {
        return $this->reference;
    }

    public function set_reference($reference)
    {
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

    public function get_features()
    {
        return $this->features;
    }

    public function set_features($features)
    {
        $this->features = $features;
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
