<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class sharedpassage extends BaseQuestionType {
    protected $type;
    protected $metadata;
    protected $heading;
    protected $paginated_content;
    protected $is_math;
    protected $instructor_stimulus;
    
    public function __construct(
                    $type
                        )
    {
                $this->type = $type;
            }

    /**
    * Get Feature Type \
    *  \
    * @return string $type \
    */
    public function get_type() {
        return $this->type;
    }

    /**
    * Set Feature Type \
    *  \
    * @param string $type \
    */
    public function set_type ($type) {
        $this->type = $type;
    }

    /**
    * Get Metadata \
    * Additional data for the Passage \
    * @return sharedpassage_metadata $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set Metadata \
    * Additional data for the Passage \
    * @param sharedpassage_metadata $metadata \
    */
    public function set_metadata (sharedpassage_metadata $metadata) {
        $this->metadata = $metadata;
    }

    /**
    * Get Heading \
    * Heading of the Passage \
    * @return string $heading \
    */
    public function get_heading() {
        return $this->heading;
    }

    /**
    * Set Heading \
    * Heading of the Passage \
    * @param string $heading \
    */
    public function set_heading ($heading) {
        $this->heading = $heading;
    }

    /**
    * Get Enable paginated content \
    * Set to true if you want your content to be paginated \
    * @return boolean $paginated_content \
    */
    public function get_paginated_content() {
        return $this->paginated_content;
    }

    /**
    * Set Enable paginated content \
    * Set to true if you want your content to be paginated \
    * @param boolean $paginated_content \
    */
    public function set_paginated_content ($paginated_content) {
        $this->paginated_content = $paginated_content;
    }

    /**
    * Get Contains Mathematics \
    * Set to <strong>true</strong> to have LaTeX or MathML to be rendered with mathjax. \
    * @return boolean $is_math \
    */
    public function get_is_math() {
        return $this->is_math;
    }

    /**
    * Set Contains Mathematics \
    * Set to <strong>true</strong> to have LaTeX or MathML to be rendered with mathjax. \
    * @param boolean $is_math \
    */
    public function set_is_math ($is_math) {
        $this->is_math = $is_math;
    }

    /**
    * Get Instructor Stimulus \
    * It will be displayed above the sharedpassage's heading. \
    * @return string $instructor_stimulus \
    */
    public function get_instructor_stimulus() {
        return $this->instructor_stimulus;
    }

    /**
    * Set Instructor Stimulus \
    * It will be displayed above the sharedpassage's heading. \
    * @param string $instructor_stimulus \
    */
    public function set_instructor_stimulus ($instructor_stimulus) {
        $this->instructor_stimulus = $instructor_stimulus;
    }

    
    public function get_widget_type() {
    return 'feature';
    }
}

