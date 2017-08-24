<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class fillshape_shape extends BaseQuestionTypeAttribute {
    protected $type;
    protected $parts;
    protected $data_format;
    protected $row_count;
    protected $column_count;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Shape type \
    * The main shape that will be displayed. \
    * @return string $type ie. circle, rectangle, griddedRectangle  \
    */
    public function get_type() {
        return $this->type;
    }

    /**
    * Set Shape type \
    * The main shape that will be displayed. \
    * @param string $type ie. circle, rectangle, griddedRectangle  \
    */
    public function set_type ($type) {
        $this->type = $type;
    }

    /**
    * Get Shape parts \
    * An array of drop zones. \
    * @return array $parts \
    */
    public function get_parts() {
        return $this->parts;
    }

    /**
    * Set Shape parts \
    * An array of drop zones. \
    * @param array $parts \
    */
    public function set_parts (array $parts) {
        $this->parts = $parts;
    }

    /**
    * Get Data format \
    * The data format used to compare responses for validation. \
    * @return string $data_format ie. degree, fraction, percent  \
    */
    public function get_data_format() {
        return $this->data_format;
    }

    /**
    * Set Data format \
    * The data format used to compare responses for validation. \
    * @param string $data_format ie. degree, fraction, percent  \
    */
    public function set_data_format ($data_format) {
        $this->data_format = $data_format;
    }

    /**
    * Get Row count \
    * Number of rows. This only applies for the Grid shape type. Values over 10 will be ignored. \
    * @return number $row_count \
    */
    public function get_row_count() {
        return $this->row_count;
    }

    /**
    * Set Row count \
    * Number of rows. This only applies for the Grid shape type. Values over 10 will be ignored. \
    * @param number $row_count \
    */
    public function set_row_count ($row_count) {
        $this->row_count = $row_count;
    }

    /**
    * Get Column count \
    * Number of columns. This only applies for the Grid shape type. Values over 10 will be ignored. \
    * @return number $column_count \
    */
    public function get_column_count() {
        return $this->column_count;
    }

    /**
    * Set Column count \
    * Number of columns. This only applies for the Grid shape type. Values over 10 will be ignored. \
    * @param number $column_count \
    */
    public function set_column_count ($column_count) {
        $this->column_count = $column_count;
    }

    
}

