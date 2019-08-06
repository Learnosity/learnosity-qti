<?php
namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
 */
class imageclozeassociationV2_response_containers_item extends BaseQuestionTypeAttribute
{
    protected $x;
    protected $y;
    protected $height;
    protected $width;
    protected $pointer;
    protected $aria_label;
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
     * Get x coordinate \
     *  \
     * @return string $x \
     */
    public function get_x()
    {
        return $this->x;
    }

    /**
     * Set x coordinate  \
     *  \
     * @param string $x \
     */
    public function set_x($x)
    {
        $this->x = $x;
    }
    
    /**
     * Get y coordinate \
     *  \
     * @return string $y \
     */
    public function get_y()
    {
        return $this->y;
    }

    /**
     * Set y coordinate  \
     *  \
     * @param string $y \
     */
    public function set_y($y)
    {
        $this->y = $y;
    }
    
    /**
     * Get arai_label \
     *  \
     * @return string $aria_label \
     */
    public function get_aria_label()
    {
        return $this->aria_label;
    }

    /**
     * Set aria_label  \
     *  \
     * @param string $aria_label \
     */
    public function set_aria_label($aria_label)
    {
        $this->aria_label = $aria_label;
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
