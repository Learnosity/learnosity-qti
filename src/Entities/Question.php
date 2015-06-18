<?php

namespace Learnosity\Entities;

class Question extends BaseEntity
{
    private $reference;
    private $type;
    private $data;

    function __construct($type, $reference, BaseQuestionType $data)
    {
        $this->data      = $data;
        $this->reference = $reference;
        $this->type      = $type;
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

    /**
     * @override
     **/
    public function to_array()
    {
        $question = get_object_vars($this);
        $question['data'] = $this->data->to_array();
        return $question;
    }
}
