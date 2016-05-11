<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.84.0","feedback":"v2.71.0","features":"v2.84.0"}
*/
class graphplotting_axis_x extends BaseQuestionTypeAttribute {
    protected $ticks_distance;
    protected $hide_ticks;
    protected $draw_labels;
    protected $comma_in_label;
    protected $show_first_arrow;
    protected $show_last_arrow;
    protected $show_axis_label;
    protected $axis_label;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Ticks distance \
    * The distance between the ticks displayed in the axis \
    * @return number $ticks_distance \
    */
    public function get_ticks_distance() {
        return $this->ticks_distance;
    }

    /**
    * Set Ticks distance \
    * The distance between the ticks displayed in the axis \
    * @param number $ticks_distance \
    */
    public function set_ticks_distance ($ticks_distance) {
        $this->ticks_distance = $ticks_distance;
    }

    /**
    * Get Hide ticks \
    * Defines if ticks are rendered in the axis \
    * @return boolean $hide_ticks \
    */
    public function get_hide_ticks() {
        return $this->hide_ticks;
    }

    /**
    * Set Hide ticks \
    * Defines if ticks are rendered in the axis \
    * @param boolean $hide_ticks \
    */
    public function set_hide_ticks ($hide_ticks) {
        $this->hide_ticks = $hide_ticks;
    }

    /**
    * Get Draw labels \
    * Defines if labels are rendered in the axis \
    * @return boolean $draw_labels \
    */
    public function get_draw_labels() {
        return $this->draw_labels;
    }

    /**
    * Set Draw labels \
    * Defines if labels are rendered in the axis \
    * @param boolean $draw_labels \
    */
    public function set_draw_labels ($draw_labels) {
        $this->draw_labels = $draw_labels;
    }

    /**
    * Get Comma in label \
    * Inserts comma to separate thousands in a number \
    * @return boolean $comma_in_label \
    */
    public function get_comma_in_label() {
        return $this->comma_in_label;
    }

    /**
    * Set Comma in label \
    * Inserts comma to separate thousands in a number \
    * @param boolean $comma_in_label \
    */
    public function set_comma_in_label ($comma_in_label) {
        $this->comma_in_label = $comma_in_label;
    }

    /**
    * Get Show first arrow \
    * Defines if an arrow should be rendered in the lower end of the axis \
    * @return boolean $show_first_arrow \
    */
    public function get_show_first_arrow() {
        return $this->show_first_arrow;
    }

    /**
    * Set Show first arrow \
    * Defines if an arrow should be rendered in the lower end of the axis \
    * @param boolean $show_first_arrow \
    */
    public function set_show_first_arrow ($show_first_arrow) {
        $this->show_first_arrow = $show_first_arrow;
    }

    /**
    * Get Show last arrow \
    * Defines if an arrow should be rendered in the higher end of the axis \
    * @return boolean $show_last_arrow \
    */
    public function get_show_last_arrow() {
        return $this->show_last_arrow;
    }

    /**
    * Set Show last arrow \
    * Defines if an arrow should be rendered in the higher end of the axis \
    * @param boolean $show_last_arrow \
    */
    public function set_show_last_arrow ($show_last_arrow) {
        $this->show_last_arrow = $show_last_arrow;
    }

    /**
    * Get Show axis label \
    * Defines if the axis label should be rendered \
    * @return boolean $show_axis_label \
    */
    public function get_show_axis_label() {
        return $this->show_axis_label;
    }

    /**
    * Set Show axis label \
    * Defines if the axis label should be rendered \
    * @param boolean $show_axis_label \
    */
    public function set_show_axis_label ($show_axis_label) {
        $this->show_axis_label = $show_axis_label;
    }

    /**
    * Get Axis label \
    * Defines the label to be rendered next to X axis \
    * @return string $axis_label \
    */
    public function get_axis_label() {
        return $this->axis_label;
    }

    /**
    * Set Axis label \
    * Defines the label to be rendered next to X axis \
    * @param string $axis_label \
    */
    public function set_axis_label ($axis_label) {
        $this->axis_label = $axis_label;
    }

    
}

