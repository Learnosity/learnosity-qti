<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

class mcq extends BaseQuestionType {
    protected bool $is_math;
    protected mcq_metadata $metadata;
    protected string $stimulus = '';
    protected string $stimulus_review;
    protected string $instructor_stimulus;
    protected string $type;
    protected mcq_ui_style $ui_style;
    protected int $feedback_attempts;
    protected bool $instant_feedback;
    protected mcq_validation $validation;
    protected string $description;
    protected array $options;
    protected bool $multiple_responses;
    protected bool $shuffle_options;
    
    public function __construct($type,array $options)
    {
        $this->type = $type;
        $this->options = $options;
    }

    /**
    * Get Contains math \
    * Set to <strong>true</strong> to have LaTeX or MathML contents to be rendered with mathjax. \
    * @return boolean $is_math \
    */
    public function get_is_math(): bool
    {
        return $this->is_math;
    }

    /**
    * Set Contains math \
    * Set to <strong>true</strong> to have LaTeX or MathML contents to be rendered with mathjax. \
    *
    * @param boolean $is_math \
    */
    public function set_is_math(bool $is_math): void
    {
        $this->is_math = $is_math;
    }

    /**
    * Get metadata
    */
    public function get_metadata(): mcq_metadata
    {
        return $this->metadata ?? new mcq_metadata();
    }

    /**
    * Set metadata
    */
    public function set_metadata (mcq_metadata $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
    * Get Stimulus
     *
    * The question stimulus. Can include text, tables, images.
    */
    public function get_stimulus(): string
    {
        return $this->stimulus;
    }

    /**
    * Set Stimulus
     *
    * The question stimulus. Can include text, tables, images.
    */
    public function set_stimulus(string $stimulus): void
    {
        $this->stimulus = $stimulus;
    }

    /**
    * Get Stimulus (review only) \
    * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed <strong>only</strong> in review state rende
	red <strong>above</strong> the response area. Supports embedded <a href="http://docs.learnosity.com/questionsapi/feature
	types.php" target="_blank">Feature &lt;span&gt; tags</a>. Will override stimulus in review state. \
    * @return string $stimulus_review \
    */
    public function get_stimulus_review(): string
    {
        return $this->stimulus_review;
    }

    /**
     * Set Stimulus (review only)
     *
     * HTML/Text content displayed only in review state rendered above the
     * response area. Will override stimulus in review state.
     */
    public function set_stimulus_review (string $stimulus_review): void
    {
        $this->stimulus_review = $stimulus_review;
    }

    /**
     * Get Instructor stimulus
     *
     * HTML/Text content displayed when showInstructorStimulus is
	 * set to true on the activity.
    */
    public function get_instructor_stimulus(): string
    {
        return $this->instructor_stimulus;
    }

    /**
    * Set Instructor stimulus
    */
    public function set_instructor_stimulus (string $instructor_stimulus): void
    {
        $this->instructor_stimulus = $instructor_stimulus;
    }

    /**
    * Get Question type
    */
    public function get_type(): string
    {
        return $this->type;
    }

    /**
    * Set Question type
    */
    public function set_type(string $type): void
    {
        $this->type = $type;
    }

    /**
    * Get ui_style
    */
    public function get_ui_style(): mcq_ui_style
    {
        return $this->ui_style;
    }

    /**
    * Set ui_style
    */
    public function set_ui_style (mcq_ui_style $ui_style): void
    {
        $this->ui_style = $ui_style;
    }

    /**
     * Get Check answer attempts
     *
     * If instant_feedback is true, this field determines how many times the user
     * can click on the 'Check Answer' button. 0 means unlimited.
    * @return number $feedback_attempts \
    */
    public function get_feedback_attempts(): int {
        return $this->feedback_attempts;
    }

    /**
    * Set Check answer attempts
    *
    * If instant_feedback is true, this field determines how many times the user
    * can click on the 'Check Answer' button. 0 means unlimited.
    */
    public function set_feedback_attempts (int $feedback_attempts): void
    {
        $this->feedback_attempts = $feedback_attempts;
    }

    /**
     * Get instant feedback
     *
     * Flag to determine whether to display a 'Check Answer' button to provide
     * instant feedback to the user.
     */
    public function get_instant_feedback(): bool
    {
        return $this->instant_feedback;
    }

    /**
    * Set Provide instant feedback \
    * Flag to determine whether to display a 'Check Answer' button to provide instant feedback to the user. \
    */
    public function set_instant_feedback(bool $instant_feedback): void
    {
        $this->instant_feedback = $instant_feedback;
    }

    /**
    * Get Set correct answer(s) \
    * In this section, configure the correct answer(s) for the question. \
    */
    public function get_validation(): mcq_validation
    {
        return $this->validation;
    }

    /**
    * Set correct answer(s)
    *
    * In this section, configure the correct answer(s) for the question.
    */
    public function set_validation (mcq_validation $validation): void
    {
        $this->validation = $validation;
    }

    /**
     * Get Description (deprecated)
     *
     * Deprecated See stimulus_review. Description of the question and its
     * context to be displayed. It supports HTML entities.
     */
    public function get_description(): string
    {
        return $this->description;
    }

    /**
    * Set Description (deprecated) \
    * <span class="label label-danger">Deprecated</span> See <em>stimulus_review</em>. <br />
* Description of the question and
	 * its context to be displayed.
* It <a data-toggle="modal" href="#supportedTags">supports HTML entities</a>. \
    *
     * @param string $description \
    */
    public function set_description (string $description): void
    {
        $this->description = $description;
    }

    /**
    * Get Multiple choice options \
    * Options support <a data-toggle='modal' href='#supportedClozeTemplateTags'>HTML</a> for formatting or MathJax syntax. \
    * @return array $options \
    */
    public function get_options(): array
    {
        return $this->options;
    }

    /**
     * Set Multiple choice options
     *
     * Options support HTML for formatting or MathJax syntax.
     *
     * @noinspection PhpUnused
     */
    public function set_options (array $options): void
    {
        $this->options = $options;
    }

    /**
     * Get Multiple responses
     *
     * If multiple_responses is true the user will be able to select multiple
     * responses using a checkbox for each response.
     */
    public function get_multiple_responses(): bool
    {
        return $this->multiple_responses;
    }

    /**
     * Set Multiple responses
     *
     * If multiple_responses is true the user will be able to select multiple
     * responses using a checkbox for each response.
     */
    public function set_multiple_responses(bool $multiple_responses): void
    {
        $this->multiple_responses = $multiple_responses;
    }

    /**
    * Get Shuffle options
    */
    public function get_shuffle_options(): bool
    {
        return $this->shuffle_options;
    }

    /**
    * Set Shuffle options
    */
    public function set_shuffle_options(bool $shuffle_options): void
    {
        $this->shuffle_options = $shuffle_options;
    }

    public function get_widget_type(): string
    {
        return 'response';
    }
}

