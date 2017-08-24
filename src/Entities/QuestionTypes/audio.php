<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class audio extends BaseQuestionType {
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $instructor_stimulus;
    protected $type;
    protected $ui_style;
    protected $validation;
    protected $description;
    protected $max_length;
    protected $overwrite_warning;
    protected $recording_cue;
    protected $show_download_link;
    protected $silence_stop_duration;
    
    public function __construct(
                    $type
                        )
    {
                $this->type = $type;
            }

    /**
    * Get Contains math \
    * Set to <strong>true</strong> to have LaTeX or MathML contents to be rendered with mathjax. \
    * @return boolean $is_math \
    */
    public function get_is_math() {
        return $this->is_math;
    }

    /**
    * Set Contains math \
    * Set to <strong>true</strong> to have LaTeX or MathML contents to be rendered with mathjax. \
    * @param boolean $is_math \
    */
    public function set_is_math ($is_math) {
        $this->is_math = $is_math;
    }

    /**
    * Get metadata \
    *  \
    * @return audio_metadata $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set metadata \
    *  \
    * @param audio_metadata $metadata \
    */
    public function set_metadata (audio_metadata $metadata) {
        $this->metadata = $metadata;
    }

    /**
    * Get Stimulus \
    * The question stimulus. Can include text, tables, images. \
    * @return string $stimulus \
    */
    public function get_stimulus() {
        return $this->stimulus;
    }

    /**
    * Set Stimulus \
    * The question stimulus. Can include text, tables, images. \
    * @param string $stimulus \
    */
    public function set_stimulus ($stimulus) {
        $this->stimulus = $stimulus;
    }

    /**
    * Get Stimulus (review only) \
    * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed <strong>only</strong> in review state rende
	red <strong>above</strong> the response area. Supports embedded <a href="http://docs.learnosity.com/questionsapi/feature
	types.php" target="_blank">Feature &lt;span&gt; tags</a>. Will override stimulus in review state. \
    * @return string $stimulus_review \
    */
    public function get_stimulus_review() {
        return $this->stimulus_review;
    }

    /**
    * Set Stimulus (review only) \
    * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed <strong>only</strong> in review state rende
	red <strong>above</strong> the response area. Supports embedded <a href="http://docs.learnosity.com/questionsapi/feature
	types.php" target="_blank">Feature &lt;span&gt; tags</a>. Will override stimulus in review state. \
    * @param string $stimulus_review \
    */
    public function set_stimulus_review ($stimulus_review) {
        $this->stimulus_review = $stimulus_review;
    }

    /**
    * Get Instructor stimulus \
    * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed when <code>showInstructorStimulus</code> is
	 set to <code>true</code> on the activity. Supports embedded <a href="http://docs.learnosity.com/questionsapi/featuretyp
	es.php" target="_blank">Feature &lt;span&gt; tags</a>. \
    * @return string $instructor_stimulus \
    */
    public function get_instructor_stimulus() {
        return $this->instructor_stimulus;
    }

    /**
    * Set Instructor stimulus \
    * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed when <code>showInstructorStimulus</code> is
	 set to <code>true</code> on the activity. Supports embedded <a href="http://docs.learnosity.com/questionsapi/featuretyp
	es.php" target="_blank">Feature &lt;span&gt; tags</a>. \
    * @param string $instructor_stimulus \
    */
    public function set_instructor_stimulus ($instructor_stimulus) {
        $this->instructor_stimulus = $instructor_stimulus;
    }

    /**
    * Get Question type \
    *  \
    * @return string $type \
    */
    public function get_type() {
        return $this->type;
    }

    /**
    * Set Question type \
    *  \
    * @param string $type \
    */
    public function set_type ($type) {
        $this->type = $type;
    }

    /**
    * Get ui_style \
    *  \
    * @return audio_ui_style $ui_style \
    */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
    * Set ui_style \
    *  \
    * @param audio_ui_style $ui_style \
    */
    public function set_ui_style (audio_ui_style $ui_style) {
        $this->ui_style = $ui_style;
    }

    /**
    * Get Validation \
    * In this section, configure the correct answer(s) for the question. \
    * @return audio_validation $validation \
    */
    public function get_validation() {
        return $this->validation;
    }

    /**
    * Set Validation \
    * In this section, configure the correct answer(s) for the question. \
    * @param audio_validation $validation \
    */
    public function set_validation (audio_validation $validation) {
        $this->validation = $validation;
    }

    /**
    * Get Description (deprecated) \
    * <span class="label label-danger">Deprecated</span> See <em>stimulus_review</em>. <br />
Description of the question and
	 its context to be displayed. 
It <a data-toggle="modal" href="#supportedTags">supports HTML entities</a>. \
    * @return string $description \
    */
    public function get_description() {
        return $this->description;
    }

    /**
    * Set Description (deprecated) \
    * <span class="label label-danger">Deprecated</span> See <em>stimulus_review</em>. <br />
Description of the question and
	 its context to be displayed. 
It <a data-toggle="modal" href="#supportedTags">supports HTML entities</a>. \
    * @param string $description \
    */
    public function set_description ($description) {
        $this->description = $description;
    }

    /**
    * Get Maximum length (seconds) \
    * The length of audio, in seconds, allowed to be recorded by the student. Maximum value is 3600 (1 hour), default value is
	 600 (10 minutes) \
    * @return number $max_length \
    */
    public function get_max_length() {
        return $this->max_length;
    }

    /**
    * Set Maximum length (seconds) \
    * The length of audio, in seconds, allowed to be recorded by the student. Maximum value is 3600 (1 hour), default value is
	 600 (10 minutes) \
    * @param number $max_length \
    */
    public function set_max_length ($max_length) {
        $this->max_length = $max_length;
    }

    /**
    * Get Overwrite warning \
    * Set to false to suppress the overwrite warning when user attempts to re-record. \
    * @return boolean $overwrite_warning \
    */
    public function get_overwrite_warning() {
        return $this->overwrite_warning;
    }

    /**
    * Set Overwrite warning \
    * Set to false to suppress the overwrite warning when user attempts to re-record. \
    * @param boolean $overwrite_warning \
    */
    public function set_overwrite_warning ($overwrite_warning) {
        $this->overwrite_warning = $overwrite_warning;
    }

    /**
    * Get Recording cue \
    * Set to false if the beep is NOT to be played before recording is started. \
    * @return boolean $recording_cue \
    */
    public function get_recording_cue() {
        return $this->recording_cue;
    }

    /**
    * Set Recording cue \
    * Set to false if the beep is NOT to be played before recording is started. \
    * @param boolean $recording_cue \
    */
    public function set_recording_cue ($recording_cue) {
        $this->recording_cue = $recording_cue;
    }

    /**
    * Get Show download link \
    * A link to download the student's recorded audio file will appear in review state. \
    * @return boolean $show_download_link \
    */
    public function get_show_download_link() {
        return $this->show_download_link;
    }

    /**
    * Set Show download link \
    * A link to download the student's recorded audio file will appear in review state. \
    * @param boolean $show_download_link \
    */
    public function set_show_download_link ($show_download_link) {
        $this->show_download_link = $show_download_link;
    }

    /**
    * Get Silence stop duration \
    * Duration of audio silence, in seconds, that is allowed before recording is stopped. This value is set to 0 by default, w
	hich means the silence detection is turned off. \
    * @return number $silence_stop_duration \
    */
    public function get_silence_stop_duration() {
        return $this->silence_stop_duration;
    }

    /**
    * Set Silence stop duration \
    * Duration of audio silence, in seconds, that is allowed before recording is stopped. This value is set to 0 by default, w
	hich means the silence detection is turned off. \
    * @param number $silence_stop_duration \
    */
    public function set_silence_stop_duration ($silence_stop_duration) {
        $this->silence_stop_duration = $silence_stop_duration;
    }

    
    public function get_widget_type() {
    return 'response';
    }
}

