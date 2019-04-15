<?php

namespace LearnosityQti\Entities;

class Question extends BaseEntity
{
    private $reference;
    private $type;
    private $data;
    private $widget_type;
    private $content;

    function __construct($type, $reference, BaseQuestionType $data, $content='')
    {
        $this->data        = $data;
        $this->reference   = $reference;
        $this->type        = $type;
        $this->widget_type = $data->get_widget_type();
        $this->content = $content;
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
    
    public function get_content(){
        return $this->content;
    }
    
    public function set_content($content){
        $this->content = $content;
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
