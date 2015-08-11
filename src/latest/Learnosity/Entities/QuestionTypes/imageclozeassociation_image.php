<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.68.0","feedback":"v2.35.0","features":"v2.68.0"}
*/
class imageclozeassociation_image extends BaseQuestionTypeAttribute {
    protected $src;
    protected $alt;
    protected $title;
    protected $scale;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Image URI \
    *  \
    * @return string $src \
    */
    public function get_src() {
        return $this->src;
    }

    /**
    * Set Image URI \
    *  \
    * @param string $src \
    */
    public function set_src ($src) {
        $this->src = $src;
    }

    /**
    * Get Image alternative text \
    *  \
    * @return string $alt \
    */
    public function get_alt() {
        return $this->alt;
    }

    /**
    * Set Image alternative text \
    *  \
    * @param string $alt \
    */
    public function set_alt ($alt) {
        $this->alt = $alt;
    }

    /**
    * Get Image title \
    *  \
    * @return string $title \
    */
    public function get_title() {
        return $this->title;
    }

    /**
    * Set Image title \
    *  \
    * @param string $title \
    */
    public function set_title ($title) {
        $this->title = $title;
    }

    /**
    * Get Image scale \
    * Allow image to be scaled along with font size \
    * @return boolean $scale \
    */
    public function get_scale() {
        return $this->scale;
    }

    /**
    * Set Image scale \
    * Allow image to be scaled along with font size \
    * @param boolean $scale \
    */
    public function set_scale ($scale) {
        $this->scale = $scale;
    }

    
}

