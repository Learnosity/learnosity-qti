<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class hotspot_area_attributes_individual_item extends BaseQuestionTypeAttribute {
    protected $area;
    protected $label;
    protected $aria_label;
    protected $fill;
    protected $stroke;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Area \
    *  \
    * @return string $area ie.   \
    */
    public function get_area() {
        return $this->area;
    }

    /**
    * Set Area \
    *  \
    * @param string $area ie.   \
    */
    public function set_area ($area) {
        $this->area = $area;
    }

    /**
    * Get Label \
    * A label for the hotspot that is not shown to the student (unless they are checking answers), but is useful when reviewin
	g student responses. \
    * @return string $label \
    */
    public function get_label() {
        return $this->label;
    }

    /**
    * Set Label \
    * A label for the hotspot that is not shown to the student (unless they are checking answers), but is useful when reviewin
	g student responses. \
    * @param string $label \
    */
    public function set_label ($label) {
        $this->label = $label;
    }

    /**
    * Get Aria label \
    * A description of the hotspot that will be available to screen readers. \
    * @return string $aria_label \
    */
    public function get_aria_label() {
        return $this->aria_label;
    }

    /**
    * Set Aria label \
    * A description of the hotspot that will be available to screen readers. \
    * @param string $aria_label \
    */
    public function set_aria_label ($aria_label) {
        $this->aria_label = $aria_label;
    }

    /**
    * Get Fill \
    * An RGBA string defining the fill for the hotspot \
    * @return string $fill \
    */
    public function get_fill() {
        return $this->fill;
    }

    /**
    * Set Fill \
    * An RGBA string defining the fill for the hotspot \
    * @param string $fill \
    */
    public function set_fill ($fill) {
        $this->fill = $fill;
    }

    /**
    * Get Outline color \
    * An RGBA string defining the stroke for the hotspot \
    * @return string $stroke \
    */
    public function get_stroke() {
        return $this->stroke;
    }

    /**
    * Set Outline color \
    * An RGBA string defining the stroke for the hotspot \
    * @param string $stroke \
    */
    public function set_stroke ($stroke) {
        $this->stroke = $stroke;
    }

    
}

