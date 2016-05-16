<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.84.0","feedback":"v2.71.0","features":"v2.84.0"}
*/
class classification_ui_style extends BaseQuestionTypeAttribute {
    protected $possibility_list_position;
    protected $fontsize;
    protected $validation_stem_numeration;
    protected $column_count;
    protected $row_count;
    protected $column_titles;
    protected $row_header;
    protected $row_titles;
    protected $row_min_height;
    protected $row_titles_width;
    protected $show_drag_handle;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Possibility List Position \
    * Defines where the possibility list sits relative to the input zone. \
    * @return string $possibility_list_position \
    */
    public function get_possibility_list_position() {
        return $this->possibility_list_position;
    }

    /**
    * Set Possibility List Position \
    * Defines where the possibility list sits relative to the input zone. \
    * @param string $possibility_list_position \
    */
    public function set_possibility_list_position ($possibility_list_position) {
        $this->possibility_list_position = $possibility_list_position;
    }

    /**
    * Get Font size \
    * Controls the size of base font for this question. Options are among 'small', 'normal', 'large', 'xlarge' and 'xxlarge'. \
    * @return string $fontsize ie. small, normal, large, xlarge, xxlarge  \
    */
    public function get_fontsize() {
        return $this->fontsize;
    }

    /**
    * Set Font size \
    * Controls the size of base font for this question. Options are among 'small', 'normal', 'large', 'xlarge' and 'xxlarge'. \
    * @param string $fontsize ie. small, normal, large, xlarge, xxlarge  \
    */
    public function set_fontsize ($fontsize) {
        $this->fontsize = $fontsize;
    }

    /**
    * Get Validation Stem Numeration \
    * Numeration character to be displayed to the left of the validation label. \
    * @return string $validation_stem_numeration ie. number, upper-alpha, lower-alpha  \
    */
    public function get_validation_stem_numeration() {
        return $this->validation_stem_numeration;
    }

    /**
    * Set Validation Stem Numeration \
    * Numeration character to be displayed to the left of the validation label. \
    * @param string $validation_stem_numeration ie. number, upper-alpha, lower-alpha  \
    */
    public function set_validation_stem_numeration ($validation_stem_numeration) {
        $this->validation_stem_numeration = $validation_stem_numeration;
    }

    /**
    * Get Column count \
    * Defines the number of columns the classification table will have \
    * @return number $column_count \
    */
    public function get_column_count() {
        return $this->column_count;
    }

    /**
    * Set Column count \
    * Defines the number of columns the classification table will have \
    * @param number $column_count \
    */
    public function set_column_count ($column_count) {
        $this->column_count = $column_count;
    }

    /**
    * Get Row count \
    * Defines the number of rows the classification table will have \
    * @return number $row_count \
    */
    public function get_row_count() {
        return $this->row_count;
    }

    /**
    * Set Row count \
    * Defines the number of rows the classification table will have \
    * @param number $row_count \
    */
    public function set_row_count ($row_count) {
        $this->row_count = $row_count;
    }

    /**
    * Get Column titles \
    * Column titles for the classification table, if there are more titles then columns the excess titles will be ignored \
    * @return array $column_titles \
    */
    public function get_column_titles() {
        return $this->column_titles;
    }

    /**
    * Set Column titles \
    * Column titles for the classification table, if there are more titles then columns the excess titles will be ignored \
    * @param array $column_titles \
    */
    public function set_column_titles (array $column_titles) {
        $this->column_titles = $column_titles;
    }

    /**
    * Get Row header \
    * Row header for the classification table, if there is no row title this attribute will be ignored. \
    * @return string $row_header \
    */
    public function get_row_header() {
        return $this->row_header;
    }

    /**
    * Set Row header \
    * Row header for the classification table, if there is no row title this attribute will be ignored. \
    * @param string $row_header \
    */
    public function set_row_header ($row_header) {
        $this->row_header = $row_header;
    }

    /**
    * Get Row titles \
    * Row titles for the classification table, if there are more titles then rows the excess titles will be ignored \
    * @return array $row_titles \
    */
    public function get_row_titles() {
        return $this->row_titles;
    }

    /**
    * Set Row titles \
    * Row titles for the classification table, if there are more titles then rows the excess titles will be ignored \
    * @param array $row_titles \
    */
    public function set_row_titles (array $row_titles) {
        $this->row_titles = $row_titles;
    }

    /**
    * Get Row min height \
    * Minimum height for the input table rows. \
    * @return string $row_min_height \
    */
    public function get_row_min_height() {
        return $this->row_min_height;
    }

    /**
    * Set Row min height \
    * Minimum height for the input table rows. \
    * @param string $row_min_height \
    */
    public function set_row_min_height ($row_min_height) {
        $this->row_min_height = $row_min_height;
    }

    /**
    * Get Row titles width \
    * The width of the column containing the row titles in the classification table \
    * @return string $row_titles_width \
    */
    public function get_row_titles_width() {
        return $this->row_titles_width;
    }

    /**
    * Set Row titles width \
    * The width of the column containing the row titles in the classification table \
    * @param string $row_titles_width \
    */
    public function set_row_titles_width ($row_titles_width) {
        $this->row_titles_width = $row_titles_width;
    }

    /**
    * Get Show drag handle \
    * Determines whether to show the drag handle. \
    * @return boolean $show_drag_handle \
    */
    public function get_show_drag_handle() {
        return $this->show_drag_handle;
    }

    /**
    * Set Show drag handle \
    * Determines whether to show the drag handle. \
    * @param boolean $show_drag_handle \
    */
    public function set_show_drag_handle ($show_drag_handle) {
        $this->show_drag_handle = $show_drag_handle;
    }

    
}

