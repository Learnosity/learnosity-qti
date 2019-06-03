<?php
namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
 */
class imageclozeassociationV2_response_container extends BaseQuestionTypeAttribute
{

    protected $height;
    protected $width;
    protected $pointer;
    protected $vertical_top;
    protected $wordwrap;

    public function __construct(
    )
    {
        
    }

    /**
     * Get Height (px) \
     *  \
     * @return string $height \
     */
    public function get_height()
    {
        return $this->height;
    }

    /**
     * Set Height (px) \
     *  \
     * @param string $height \
     */
    public function set_height($height)
    {
        $this->height = $height;
    }

    /**
     * Get Width (px) \
     *  \
     * @return string $width \
     */
    public function get_width()
    {
        return $this->width;
    }

    /**
     * Set Width (px) \
     *  \
     * @param string $width \
     */
    public function set_width($width)
    {
        $this->width = $width;
    }

    /**
     * Get Pointer \
     * Add response pointer next to the response container. Values can be one of 'top', 'right', 'bottom', 'left' \
     * @return string $pointer \
     */
    public function get_pointer()
    {
        return $this->pointer;
    }

    /**
     * Set Pointer \
     * Add response pointer next to the response container. Values can be one of 'top', 'right', 'bottom', 'left' \
     * @param string $pointer \
     */
    public function set_pointer($pointer)
    {
        $this->pointer = $pointer;
    }

    /**
     * Get Vertical top \
     * Response container will always stay at the top left position if this attribute value is set to true \
     * @return boolean $vertical_top \
     */
    public function get_vertical_top()
    {
        return $this->vertical_top;
    }

    /**
     * Set Vertical top \
     * Response container will always stay at the top left position if this attribute value is set to true \
     * @param boolean $vertical_top \
     */
    public function set_vertical_top($vertical_top)
    {
        $this->vertical_top = $vertical_top;
    }

    /**
     * Get Wordwrap \
     * Determines if the possible response text should wrap or show an ellipsis when placed in a response container. \
     * @return boolean $wordwrap \
     */
    public function get_wordwrap()
    {
        return $this->wordwrap;
    }

    /**
     * Set Wordwrap \
     * Determines if the possible response text should wrap or show an ellipsis when placed in a response container. \
     * @param boolean $wordwrap \
     */
    public function set_wordwrap($wordwrap)
    {
        $this->wordwrap = $wordwrap;
    }
}
