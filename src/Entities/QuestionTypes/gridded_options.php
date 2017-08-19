<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class gridded_options extends BaseQuestionTypeAttribute {
    protected $columns;
    protected $plus_minus_column;
    protected $fixed_decimal;
    protected $range;
    protected $default_inputs;
    protected $fraction_slash;
    protected $decimal_column;
    protected $floating_decimal;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Columns \
    * Number of grid columns. \
    * @return number $columns \
    */
    public function get_columns() {
        return $this->columns;
    }

    /**
    * Set Columns \
    * Number of grid columns. \
    * @param number $columns \
    */
    public function set_columns ($columns) {
        $this->columns = $columns;
    }

    /**
    * Get Plus/minus column \
    * Column used for indicating whether the number is positive or negative. \
    * @return boolean $plus_minus_column \
    */
    public function get_plus_minus_column() {
        return $this->plus_minus_column;
    }

    /**
    * Set Plus/minus column \
    * Column used for indicating whether the number is positive or negative. \
    * @param boolean $plus_minus_column \
    */
    public function set_plus_minus_column ($plus_minus_column) {
        $this->plus_minus_column = $plus_minus_column;
    }

    /**
    * Get Fixed decimal \
    * Allows specifying the column number for fixed decimal position. \
    * @return boolean $fixed_decimal \
    */
    public function get_fixed_decimal() {
        return $this->fixed_decimal;
    }

    /**
    * Set Fixed decimal \
    * Allows specifying the column number for fixed decimal position. \
    * @param boolean $fixed_decimal \
    */
    public function set_fixed_decimal ($fixed_decimal) {
        $this->fixed_decimal = $fixed_decimal;
    }

    /**
    * Get Range of numbers to use \
    * Specifies the range of numbers to be displayed. By default the range is set to 0-9. \
    * @return string $range \
    */
    public function get_range() {
        return $this->range;
    }

    /**
    * Set Range of numbers to use \
    * Specifies the range of numbers to be displayed. By default the range is set to 0-9. \
    * @param string $range \
    */
    public function set_range ($range) {
        $this->range = $range;
    }

    /**
    * Get Default values \
    * Default inputs. \
    * @return object $default_inputs \
    */
    public function get_default_inputs() {
        return $this->default_inputs;
    }

    /**
    * Set Default values \
    * Default inputs. \
    * @param object $default_inputs \
    */
    public function set_default_inputs ($default_inputs) {
        $this->default_inputs = $default_inputs;
    }

    /**
    * Get Fraction slash \
    * Allow user to enter fraction slash position. Can be used only once. \
    * @return boolean $fraction_slash \
    */
    public function get_fraction_slash() {
        return $this->fraction_slash;
    }

    /**
    * Set Fraction slash \
    * Allow user to enter fraction slash position. Can be used only once. \
    * @param boolean $fraction_slash \
    */
    public function set_fraction_slash ($fraction_slash) {
        $this->fraction_slash = $fraction_slash;
    }

    /**
    * Get Fixed decimal column \
    * Specify the column number for decimal. Overrides the floating decimal option. \
    * @return number $decimal_column \
    */
    public function get_decimal_column() {
        return $this->decimal_column;
    }

    /**
    * Set Fixed decimal column \
    * Specify the column number for decimal. Overrides the floating decimal option. \
    * @param number $decimal_column \
    */
    public function set_decimal_column ($decimal_column) {
        $this->decimal_column = $decimal_column;
    }

    /**
    * Get Floating decimal \
    * Allow user to enter the decimal point position. Can not be used together with fixed decimal. \
    * @return boolean $floating_decimal \
    */
    public function get_floating_decimal() {
        return $this->floating_decimal;
    }

    /**
    * Set Floating decimal \
    * Allow user to enter the decimal point position. Can not be used together with fixed decimal. \
    * @param boolean $floating_decimal \
    */
    public function set_floating_decimal ($floating_decimal) {
        $this->floating_decimal = $floating_decimal;
    }

    
}

