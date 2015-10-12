<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class graphplotting_annotation extends BaseQuestionTypeAttribute {
    protected $title;
    protected $label_top;
    protected $label_right;
    protected $label_bottom;
    protected $label_left;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Title \
    * The graph title \
    * @return string $title \
    */
    public function get_title() {
        return $this->title;
    }

    /**
    * Set Title \
    * The graph title \
    * @param string $title \
    */
    public function set_title ($title) {
        $this->title = $title;
    }

    /**
    * Get Label top \
    * Label for the top of the graph \
    * @return string $label_top \
    */
    public function get_label_top() {
        return $this->label_top;
    }

    /**
    * Set Label top \
    * Label for the top of the graph \
    * @param string $label_top \
    */
    public function set_label_top ($label_top) {
        $this->label_top = $label_top;
    }

    /**
    * Get Label right \
    * Label for the right of the graph \
    * @return string $label_right \
    */
    public function get_label_right() {
        return $this->label_right;
    }

    /**
    * Set Label right \
    * Label for the right of the graph \
    * @param string $label_right \
    */
    public function set_label_right ($label_right) {
        $this->label_right = $label_right;
    }

    /**
    * Get Label bottom \
    * Label for the bottom of the graph \
    * @return string $label_bottom \
    */
    public function get_label_bottom() {
        return $this->label_bottom;
    }

    /**
    * Set Label bottom \
    * Label for the bottom of the graph \
    * @param string $label_bottom \
    */
    public function set_label_bottom ($label_bottom) {
        $this->label_bottom = $label_bottom;
    }

    /**
    * Get Label left \
    * Label for the left of the graph \
    * @return string $label_left \
    */
    public function get_label_left() {
        return $this->label_left;
    }

    /**
    * Set Label left \
    * Label for the left of the graph \
    * @param string $label_left \
    */
    public function set_label_left ($label_left) {
        $this->label_left = $label_left;
    }

    
}

