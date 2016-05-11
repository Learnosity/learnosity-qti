<?php

namespace LearnosityQti\Entities\Activity;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.84.0","feedback":"v2.71.0","features":"v2.84.0"}
*/
class activity_data_config extends BaseQuestionTypeAttribute {
    protected $ui_style;
    protected $administration;
    protected $time;
    protected $navigation;
    protected $configuration;
    
    public function __construct(
            )
    {
            }

    /**
    * Get ui_style \
    *  \
    * @return string $ui_style \
    */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
    * Set ui_style \
    *  \
    * @param string $ui_style \
    */
    public function set_ui_style ($ui_style) {
        $this->ui_style = $ui_style;
    }

    /**
    * Get administration \
    *  \
    * @return string $administration \
    */
    public function get_administration() {
        return $this->administration;
    }

    /**
    * Set administration \
    *  \
    * @param string $administration \
    */
    public function set_administration ($administration) {
        $this->administration = $administration;
    }

    /**
    * Get time \
    *  \
    * @return activity_data_config_time $time \
    */
    public function get_time() {
        return $this->time;
    }

    /**
    * Set time \
    *  \
    * @param activity_data_config_time $time \
    */
    public function set_time (activity_data_config_time $time) {
        $this->time = $time;
    }

    /**
    * Get navigation \
    *  \
    * @return activity_data_config_navigation $navigation \
    */
    public function get_navigation() {
        return $this->navigation;
    }

    /**
    * Set navigation \
    *  \
    * @param activity_data_config_navigation $navigation \
    */
    public function set_navigation (activity_data_config_navigation $navigation) {
        $this->navigation = $navigation;
    }

    /**
    * Get configuration \
    *  \
    * @return activity_data_config_configuration $configuration \
    */
    public function get_configuration() {
        return $this->configuration;
    }

    /**
    * Set configuration \
    *  \
    * @param activity_data_config_configuration $configuration \
    */
    public function set_configuration (activity_data_config_configuration $configuration) {
        $this->configuration = $configuration;
    }

    
}

