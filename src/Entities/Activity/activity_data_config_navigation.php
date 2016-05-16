<?php

namespace LearnosityQti\Entities\Activity;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.84.0","feedback":"v2.71.0","features":"v2.84.0"}
*/
class activity_data_config_navigation extends BaseQuestionTypeAttribute {
    protected $auto_save;
    protected $show_save;
    protected $show_submit;
    protected $skip_submit_confirmation;
    protected $show_title;
    protected $show_progress;
    protected $show_fullscreencontrol;
    protected $show_intro;
    protected $show_outro;
    protected $show_prev;
    protected $show_next;
    protected $show_itemcount;
    protected $warning_on_change;
    protected $scrolling_indicator;
    protected $scroll_to_top;
    protected $scroll_to_test;
    protected $transition;
    protected $transition_speed;
    protected $show_calculator;
    protected $show_answermasking;
    protected $exit_securebrowser;
    protected $show_acknowledgements;
    protected $show_accessibility;
    
    public function __construct(
            )
    {
            }

    /**
    * Get auto_save \
    *  \
    * @return boolean $auto_save \
    */
    public function get_auto_save() {
        return $this->auto_save;
    }

    /**
    * Set auto_save \
    *  \
    * @param boolean $auto_save \
    */
    public function set_auto_save ($auto_save) {
        $this->auto_save = $auto_save;
    }

    /**
    * Get show_save \
    *  \
    * @return boolean $show_save \
    */
    public function get_show_save() {
        return $this->show_save;
    }

    /**
    * Set show_save \
    *  \
    * @param boolean $show_save \
    */
    public function set_show_save ($show_save) {
        $this->show_save = $show_save;
    }

    /**
    * Get show_submit \
    *  \
    * @return boolean $show_submit \
    */
    public function get_show_submit() {
        return $this->show_submit;
    }

    /**
    * Set show_submit \
    *  \
    * @param boolean $show_submit \
    */
    public function set_show_submit ($show_submit) {
        $this->show_submit = $show_submit;
    }

    /**
    * Get skip_submit_confirmation \
    *  \
    * @return boolean $skip_submit_confirmation \
    */
    public function get_skip_submit_confirmation() {
        return $this->skip_submit_confirmation;
    }

    /**
    * Set skip_submit_confirmation \
    *  \
    * @param boolean $skip_submit_confirmation \
    */
    public function set_skip_submit_confirmation ($skip_submit_confirmation) {
        $this->skip_submit_confirmation = $skip_submit_confirmation;
    }

    /**
    * Get show_title \
    *  \
    * @return boolean $show_title \
    */
    public function get_show_title() {
        return $this->show_title;
    }

    /**
    * Set show_title \
    *  \
    * @param boolean $show_title \
    */
    public function set_show_title ($show_title) {
        $this->show_title = $show_title;
    }

    /**
    * Get show_progress \
    *  \
    * @return boolean $show_progress \
    */
    public function get_show_progress() {
        return $this->show_progress;
    }

    /**
    * Set show_progress \
    *  \
    * @param boolean $show_progress \
    */
    public function set_show_progress ($show_progress) {
        $this->show_progress = $show_progress;
    }

    /**
    * Get show_fullscreencontrol \
    *  \
    * @return boolean $show_fullscreencontrol \
    */
    public function get_show_fullscreencontrol() {
        return $this->show_fullscreencontrol;
    }

    /**
    * Set show_fullscreencontrol \
    *  \
    * @param boolean $show_fullscreencontrol \
    */
    public function set_show_fullscreencontrol ($show_fullscreencontrol) {
        $this->show_fullscreencontrol = $show_fullscreencontrol;
    }

    /**
    * Get show_intro \
    *  \
    * @return boolean $show_intro \
    */
    public function get_show_intro() {
        return $this->show_intro;
    }

    /**
    * Set show_intro \
    *  \
    * @param boolean $show_intro \
    */
    public function set_show_intro ($show_intro) {
        $this->show_intro = $show_intro;
    }

    /**
    * Get show_outro \
    *  \
    * @return boolean $show_outro \
    */
    public function get_show_outro() {
        return $this->show_outro;
    }

    /**
    * Set show_outro \
    *  \
    * @param boolean $show_outro \
    */
    public function set_show_outro ($show_outro) {
        $this->show_outro = $show_outro;
    }

    /**
    * Get show_prev \
    *  \
    * @return boolean $show_prev \
    */
    public function get_show_prev() {
        return $this->show_prev;
    }

    /**
    * Set show_prev \
    *  \
    * @param boolean $show_prev \
    */
    public function set_show_prev ($show_prev) {
        $this->show_prev = $show_prev;
    }

    /**
    * Get show_next \
    *  \
    * @return boolean $show_next \
    */
    public function get_show_next() {
        return $this->show_next;
    }

    /**
    * Set show_next \
    *  \
    * @param boolean $show_next \
    */
    public function set_show_next ($show_next) {
        $this->show_next = $show_next;
    }

    /**
    * Get show_itemcount \
    *  \
    * @return boolean $show_itemcount \
    */
    public function get_show_itemcount() {
        return $this->show_itemcount;
    }

    /**
    * Set show_itemcount \
    *  \
    * @param boolean $show_itemcount \
    */
    public function set_show_itemcount ($show_itemcount) {
        $this->show_itemcount = $show_itemcount;
    }

    /**
    * Get warning_on_change \
    *  \
    * @return boolean $warning_on_change \
    */
    public function get_warning_on_change() {
        return $this->warning_on_change;
    }

    /**
    * Set warning_on_change \
    *  \
    * @param boolean $warning_on_change \
    */
    public function set_warning_on_change ($warning_on_change) {
        $this->warning_on_change = $warning_on_change;
    }

    /**
    * Get scrolling_indicator \
    *  \
    * @return boolean $scrolling_indicator \
    */
    public function get_scrolling_indicator() {
        return $this->scrolling_indicator;
    }

    /**
    * Set scrolling_indicator \
    *  \
    * @param boolean $scrolling_indicator \
    */
    public function set_scrolling_indicator ($scrolling_indicator) {
        $this->scrolling_indicator = $scrolling_indicator;
    }

    /**
    * Get scroll_to_top \
    *  \
    * @return boolean $scroll_to_top \
    */
    public function get_scroll_to_top() {
        return $this->scroll_to_top;
    }

    /**
    * Set scroll_to_top \
    *  \
    * @param boolean $scroll_to_top \
    */
    public function set_scroll_to_top ($scroll_to_top) {
        $this->scroll_to_top = $scroll_to_top;
    }

    /**
    * Get scroll_to_test \
    *  \
    * @return boolean $scroll_to_test \
    */
    public function get_scroll_to_test() {
        return $this->scroll_to_test;
    }

    /**
    * Set scroll_to_test \
    *  \
    * @param boolean $scroll_to_test \
    */
    public function set_scroll_to_test ($scroll_to_test) {
        $this->scroll_to_test = $scroll_to_test;
    }

    /**
    * Get transition \
    *  \
    * @return boolean $transition \
    */
    public function get_transition() {
        return $this->transition;
    }

    /**
    * Set transition \
    *  \
    * @param boolean $transition \
    */
    public function set_transition ($transition) {
        $this->transition = $transition;
    }

    /**
    * Get transition_speed \
    *  \
    * @return integer $transition_speed \
    */
    public function get_transition_speed() {
        return $this->transition_speed;
    }

    /**
    * Set transition_speed \
    *  \
    * @param integer $transition_speed \
    */
    public function set_transition_speed ($transition_speed) {
        $this->transition_speed = $transition_speed;
    }

    /**
    * Get show_calculator \
    *  \
    * @return boolean $show_calculator \
    */
    public function get_show_calculator() {
        return $this->show_calculator;
    }

    /**
    * Set show_calculator \
    *  \
    * @param boolean $show_calculator \
    */
    public function set_show_calculator ($show_calculator) {
        $this->show_calculator = $show_calculator;
    }

    /**
    * Get show_answermasking \
    *  \
    * @return boolean $show_answermasking \
    */
    public function get_show_answermasking() {
        return $this->show_answermasking;
    }

    /**
    * Set show_answermasking \
    *  \
    * @param boolean $show_answermasking \
    */
    public function set_show_answermasking ($show_answermasking) {
        $this->show_answermasking = $show_answermasking;
    }

    /**
    * Get exit_securebrowser \
    *  \
    * @return boolean $exit_securebrowser \
    */
    public function get_exit_securebrowser() {
        return $this->exit_securebrowser;
    }

    /**
    * Set exit_securebrowser \
    *  \
    * @param boolean $exit_securebrowser \
    */
    public function set_exit_securebrowser ($exit_securebrowser) {
        $this->exit_securebrowser = $exit_securebrowser;
    }

    /**
    * Get show_acknowledgements \
    *  \
    * @return boolean $show_acknowledgements \
    */
    public function get_show_acknowledgements() {
        return $this->show_acknowledgements;
    }

    /**
    * Set show_acknowledgements \
    *  \
    * @param boolean $show_acknowledgements \
    */
    public function set_show_acknowledgements ($show_acknowledgements) {
        $this->show_acknowledgements = $show_acknowledgements;
    }

    /**
    * Get show_accessibility \
    *  \
    * @return activity_data_config_navigation_show_accessibility $show_accessibility \
    */
    public function get_show_accessibility() {
        return $this->show_accessibility;
    }

    /**
    * Set show_accessibility \
    *  \
    * @param activity_data_config_navigation_show_accessibility $show_accessibility \
    */
    public function set_show_accessibility (activity_data_config_navigation_show_accessibility $show_accessibility) {
        $this->show_accessibility = $show_accessibility;
    }

    
}

