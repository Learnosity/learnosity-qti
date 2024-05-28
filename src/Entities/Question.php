<?php

namespace LearnosityQti\Entities;

class Question extends BaseEntity
{
    private $reference;
    private $type;
    private BaseQuestionType $data;
    private $widget_type;
    private mixed $item_reference;
    private mixed $content;
    private mixed $features;

    function __construct(
        $type,
        $reference,
        BaseQuestionType $data,
        $itemReference = '',
        $content = '',
        $features = '',
    ) {
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

    public function set_reference($reference): void
    {
        $this->reference = $reference;
    }
    
    public function get_content()
    {
        return $this->content;
    }

    public function set_content($content): void
    {
        $this->content = $content;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function set_type($type): void
    {
        $this->type = $type;
    }

    public function get_data(): BaseQuestionType
    {
        return $this->data;
    }

    public function set_data(BaseQuestionType $data): void
    {
        $this->data = $data;
    }

    public function set_item_reference($itemReference): void
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

    public function set_features($features): void
    {
        $this->features = $features;
    }

    /**
     * @override
     **/
    public function to_array(): array
    {
        $question         = get_object_vars($this);
        $question['data'] = $this->data->to_array();
        return $question;
    }
}
