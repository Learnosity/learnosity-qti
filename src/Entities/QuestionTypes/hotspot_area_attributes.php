<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.84.0","feedback":"v2.71.0","features":"v2.84.0"}
*/
class hotspot_area_attributes extends BaseQuestionTypeAttribute {
    protected $global;
    protected $individual;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Global \
    * Attributes for all hotspots. \
    * @return hotspot_area_attributes_global $global \
    */
    public function get_global() {
        return $this->global;
    }

    /**
    * Set Global \
    * Attributes for all hotspots. \
    * @param hotspot_area_attributes_global $global \
    */
    public function set_global (hotspot_area_attributes_global $global) {
        $this->global = $global;
    }

    /**
    * Get Individual \
    * Individual attributes per hotspot that have precedence over the global hotspot attributes \
    * @return array $individual \
    */
    public function get_individual() {
        return $this->individual;
    }

    /**
    * Set Individual \
    * Individual attributes per hotspot that have precedence over the global hotspot attributes \
    * @param array $individual \
    */
    public function set_individual (array $individual) {
        $this->individual = $individual;
    }

    
}

