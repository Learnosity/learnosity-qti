<?php
namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
 */
class imageclozeassociationV2 extends BaseQuestionType
{

    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $instructor_stimulus;
    protected $type;
    protected $ui_style;
    protected $feedback_attempts;
    protected $instant_feedback;
    protected $validation;
    protected $response_container;
    protected $response_containers;
    protected $image;
    protected $aria_labels;
    protected $group_possible_responses;
    protected $possible_responses;
    protected $img_src;
    protected $duplicate_responses;
    protected $shuffle_options;

    public function __construct(
    $type, imageclozeassociationV2_image $image, array $possible_responses
    )
    {
        $this->type = $type;
        $this->image = $image;
        $this->possible_responses = $possible_responses;
    }

    /**
     * Get Contains math \
     * Set to <strong>true</strong> to have LaTeX or MathML contents to be rendered with mathjax. \
     * @return boolean $is_math \
     */
    public function get_is_math()
    {
        return $this->is_math;
    }

    /**
     * Set Contains math \
     * Set to <strong>true</strong> to have LaTeX or MathML contents to be rendered with mathjax. \
     * @param boolean $is_math \
     */
    public function set_is_math($is_math)
    {
        $this->is_math = $is_math;
    }

    /**
     * Get metadata \
     *  \
     * @return imageclozeassociation_metadata $metadata \
     */
    public function get_metadata()
    {
        return $this->metadata;
    }

    /**
     * Set metadata \
     *  \
     * @param imageclozeassociation_metadata $metadata \
     */
    public function set_metadata(imageclozeassociationV2_metadata $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * Get Possible Responses \
     * The question possible_responses. Can include text, tables, images. \
     * @return string $possible_responses \
     */
    public function get_possible_responses()
    {
        return $this->possible_responses;
    }

    /**
     * Set Possible Responses \
     * The question possible_responses. Can include text, tables, images. \
     * @param string $possible_responses \
     */
    public function set_possible_responses(array $possible_responses)
    {
        $this->possible_responses = $possible_responses;
    }

    /**
     * Get Stimulus \
     * The question stimulus. Can include text, tables, images. \
     * @return string $stimulus \
     */
    public function get_stimulus()
    {
        return $this->stimulus;
    }

    /**
     * Set Stimulus \
     * The question stimulus. Can include text, tables, images. \
     * @param string $stimulus \
     */
    public function set_stimulus($stimulus)
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
    public function get_stimulus_review()
    {
        return $this->stimulus_review;
    }

    /**
     * Set Stimulus (review only) \
     * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed <strong>only</strong> in review state rende
      red <strong>above</strong> the response area. Supports embedded <a href="http://docs.learnosity.com/questionsapi/feature
      types.php" target="_blank">Feature &lt;span&gt; tags</a>. Will override stimulus in review state. \
     * @param string $stimulus_review \
     */
    public function set_stimulus_review($stimulus_review)
    {
        $this->stimulus_review = $stimulus_review;
    }

    /**
     * Get Instructor stimulus \
     * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed when <code>showInstructorStimulus</code> is
      set to <code>true</code> on the activity. Supports embedded <a href="http://docs.learnosity.com/questionsapi/featuretyp
      es.php" target="_blank">Feature &lt;span&gt; tags</a>. \
     * @return string $instructor_stimulus \
     */
    public function get_instructor_stimulus()
    {
        return $this->instructor_stimulus;
    }

    /**
     * Set Instructor stimulus \
     * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed when <code>showInstructorStimulus</code> is
      set to <code>true</code> on the activity. Supports embedded <a href="http://docs.learnosity.com/questionsapi/featuretyp
      es.php" target="_blank">Feature &lt;span&gt; tags</a>. \
     * @param string $instructor_stimulus \
     */
    public function set_instructor_stimulus($instructor_stimulus)
    {
        $this->instructor_stimulus = $instructor_stimulus;
    }

    /**
     * Get Question type \
     *  \
     * @return string $type \
     */
    public function get_type()
    {
        return $this->type;
    }

    /**
     * Set Question type \
     *  \
     * @param string $type \
     */
    public function set_type($type)
    {
        $this->type = $type;
    }

    /**
     * Get ui_style \
     *  \
     * @return imageclozeassociation_ui_style $ui_style \
     */
    public function get_ui_style()
    {
        return $this->ui_style;
    }

    /**
     * Set ui_style \
     *  \
     * @param imageclozeassociation_ui_style $ui_style \
     */
    public function set_ui_style(imageclozeassociationV2_ui_style $ui_style)
    {
        $this->ui_style = $ui_style;
    }

    /**
     * Get Check answer attempts \
     * If instant_feedback is true, this field determines how many times the user can click on the 'Check Answer' button. 0 mea
      ns unlimited. \
     * @return number $feedback_attempts \
     */
    public function get_feedback_attempts()
    {
        return $this->feedback_attempts;
    }

    /**
     * Set Check answer attempts \
     * If instant_feedback is true, this field determines how many times the user can click on the 'Check Answer' button. 0 mea
      ns unlimited. \
     * @param number $feedback_attempts \
     */
    public function set_feedback_attempts($feedback_attempts)
    {
        $this->feedback_attempts = $feedback_attempts;
    }

    /**
     * Get Provide instant feedback \
     * Flag to determine whether to display a 'Check Answer' button to provide instant feedback to the user. \
     * @return boolean $instant_feedback \
     */
    public function get_instant_feedback()
    {
        return $this->instant_feedback;
    }

    /**
     * Set Provide instant feedback \
     * Flag to determine whether to display a 'Check Answer' button to provide instant feedback to the user. \
     * @param boolean $instant_feedback \
     */
    public function set_instant_feedback($instant_feedback)
    {
        $this->instant_feedback = $instant_feedback;
    }

    /**
     * Get Set correct answer(s) \
     * In this section, configure the correct answer(s) for the question. \
     * @return imageclozeassociation_validation $validation \
     */
    public function get_validation()
    {
        return $this->validation;
    }

    /**
     * Set Set correct answer(s) \
     * In this section, configure the correct answer(s) for the question. \
     * @param imageclozeassociation_validation $validation \
     */
    public function set_validation(imageclozeassociationV2_validation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * Get Response container (global) \
     * Use Response Container (global) to make changes to all response boxes at once. \
     * @return imageclozeassociation_response_container $response_container \
     */
    public function get_response_container()
    {
        return $this->response_container;
    }

    /**
     * Set Response container (global) \
     * Use Response Container (global) to make changes to all response boxes at once. \
     * @param imageclozeassociation_response_container $response_container \
     */
    public function set_response_container(imageclozeassociationV2_response_container $response_container)
    {
        $this->response_container = $response_container;
    }

    /**
     * Get Response Container (individual) \
     * Array containing objects defining each individual response container style. \
     * @return array $response_containers \
     */
    public function get_response_containers()
    {
        return $this->response_containers;
    }

    /**
     * Set Response Container (individual) \
     * Array containing objects defining each individual response container style. \
     * @param array $response_containers \
     */
    public function set_response_containers(array $response_containers)
    {
        $this->response_containers = $response_containers;
    }

    /**
     * Get Image settings \
     * Image settings are defined here. \
     * @return imageclozeassociation_image $image \
     */
    public function get_image()
    {
        return $this->image;
    }

    /**
     * Set Image settings \
     * Image settings are defined here. \
     * @param imageclozeassociation_image $image \
     */
    public function set_image(imageclozeassociationV2_image $image)
    {
        $this->image = $image;
    }

    /**
     * Get Edit ARIA labels \
     * Text entered here will help assistive technology (e.g. a screen reader) attach a label to the image for accessibility pu
      rposes. \
     * @return array $aria_labels \
     */
    public function get_aria_labels()
    {
        return $this->aria_labels;
    }

    /**
     * Set Edit ARIA labels \
     * Text entered here will help assistive technology (e.g. a screen reader) attach a label to the image for accessibility pu
      rposes. \
     * @param array $aria_labels \
     */
    public function set_aria_labels(array $aria_labels)
    {
        $this->aria_labels = $aria_labels;
    }

    /**
     * Get Group possible responses \
     * Categorise possible responses into different groups, with each group having its own heading. \
     * @return groupPossibleResponses $group_possible_responses \
     */
    public function get_group_possible_responses()
    {
        return $this->group_possible_responses;
    }

    /**
     * Set Group possible responses \
     * Categorise possible responses into different groups, with each group having its own heading. \
     * @param groupPossibleResponses $group_possible_responses \
     */
    public function set_group_possible_responses($group_possible_responses)
    {
        $this->group_possible_responses = $group_possible_responses;
    }

    /**
     * Get Add image \
     * Absolute URI for the background image. \
     * @return string $img_src \
     */
    public function get_img_src()
    {
        return $this->img_src;
    }

    /**
     * Set Add image \
     * Absolute URI for the background image. \
     * @param string $img_src \
     */
    public function set_img_src($img_src)
    {
        $this->img_src = $img_src;
    }

    /**
     * Get Duplicate responses \
     * When true the items from the possible_responses will be reusable infinite times. \
     * @return boolean $duplicate_responses \
     */
    public function get_duplicate_responses()
    {
        return $this->duplicate_responses;
    }

    /**
     * Set Duplicate responses \
     * When true the items from the possible_responses will be reusable infinite times. \
     * @param boolean $duplicate_responses \
     */
    public function set_duplicate_responses($duplicate_responses)
    {
        $this->duplicate_responses = $duplicate_responses;
    }

    /**
     * Get Shuffle options \
     *  \
     * @return boolean $shuffle_options \
     */
    public function get_shuffle_options()
    {
        return $this->shuffle_options;
    }

    /**
     * Set Shuffle options \
     *  \
     * @param boolean $shuffle_options \
     */
    public function set_shuffle_options($shuffle_options)
    {
        $this->shuffle_options = $shuffle_options;
    }

    public function get_widget_type()
    {
        return 'response';
    }
}
