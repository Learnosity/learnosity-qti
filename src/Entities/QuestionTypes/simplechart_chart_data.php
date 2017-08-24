<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class simplechart_chart_data extends BaseQuestionTypeAttribute {
    protected $name;
    protected $data;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Name \
    * The title of the chart. \
    * @return string $name \
    */
    public function get_name() {
        return $this->name;
    }

    /**
    * Set Name \
    * The title of the chart. \
    * @param string $name \
    */
    public function set_name ($name) {
        $this->name = $name;
    }

    /**
    * Get Data \
    *  \
    * @return array $data \
    */
    public function get_data() {
        return $this->data;
    }

    /**
    * Set Data \
    *  \
    * @param array $data \
    */
    public function set_data (array $data) {
        $this->data = $data;
    }

    
}

