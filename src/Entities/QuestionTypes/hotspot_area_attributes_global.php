<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class hotspot_area_attributes_global extends BaseQuestionTypeAttribute {
    protected $fill;
    protected $stroke;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Fill color \
    * An RGBA string defining the fill for all hotspots. \
    * @return string $fill \
    */
    public function get_fill() {
        return $this->fill;
    }

    /**
    * Set Fill color \
    * An RGBA string defining the fill for all hotspots. \
    * @param string $fill \
    */
    public function set_fill ($fill) {
        $this->fill = $fill;
    }

    /**
    * Get Outline color \
    * An RGBA string defining the stroke for all hotspots. \
    * @return string $stroke \
    */
    public function get_stroke() {
        return $this->stroke;
    }

    /**
    * Set Outline color \
    * An RGBA string defining the stroke for all hotspots. \
    * @param string $stroke \
    */
    public function set_stroke ($stroke) {
        $this->stroke = $stroke;
    }

    
}

