<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class scrollablepassage extends BaseQuestionType {
    protected $data_type;
    protected $data_width;
    protected $data_height;
    protected $data_border;
    protected $data_subtract_height;

    public function __construct(
            )
    {
            }

    /**
    * Get data_type \
    * Use the value 'scrollablepassage' for this field. \
    * @return string $data_type \
    */
    public function get_data_type() {
        return $this->data_type;
    }

    /**
    * Set data_type \
    * Use the value 'scrollablepassage' for this field. \
    * @param string $data_type \
    */
    public function set_data_type ($data_type) {
        $this->data_type = $data_type;
    }

    /**
    * Get data_width \
    * Width of the scrollable passage container in pixels or percentage. \
    * @return string $data_width \
    */
    public function get_data_width() {
        return $this->data_width;
    }

    /**
    * Set data_width \
    * Width of the scrollable passage container in pixels or percentage. \
    * @param string $data_width \
    */
    public function set_data_width ($data_width) {
        $this->data_width = $data_width;
    }

    /**
    * Get data_height \
    * Height of the scrollable passage container in pixels or percentage. \
    * @return string $data_height \
    */
    public function get_data_height() {
        return $this->data_height;
    }

    /**
    * Set data_height \
    * Height of the scrollable passage container in pixels or percentage. \
    * @param string $data_height \
    */
    public function set_data_height ($data_height) {
        $this->data_height = $data_height;
    }

    /**
    * Get data_border \
    * Whether to display a wrapper around the scrollable passage container. \
    * @return boolean $data_border \
    */
    public function get_data_border() {
        return $this->data_border;
    }

    /**
    * Set data_border \
    * Whether to display a wrapper around the scrollable passage container. \
    * @param boolean $data_border \
    */
    public function set_data_border ($data_border) {
        $this->data_border = $data_border;
    }

    /**
    * Get data_subtract_height \
    * A value which reduces the height of the passage container. \
    * @return string $data_subtract_height \
    */
    public function get_data_subtract_height() {
        return $this->data_subtract_height;
    }

    /**
    * Set data_subtract_height \
    * A value which reduces the height of the passage container. \
    * @param string $data_subtract_height \
    */
    public function set_data_subtract_height ($data_subtract_height) {
        $this->data_subtract_height = $data_subtract_height;
    }


    public function get_widget_type() {
    return 'feature';
    }
}
