<?php
namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
 */
class imageclozeassociationV2_image extends BaseQuestionTypeAttribute
{

    protected $src;
    protected $alt;
    protected $title;
    protected $scale;
    protected $width;
    protected $height;

    public function __construct(
    )
    {
        
    }

    /**
     * Get Add image \
     *  \
     * @return string $src \
     */
    public function get_src()
    {
        return $this->src;
    }

    /**
     * Set Add image \
     *  \
     * @param string $src \
     */
    public function set_src($src)
    {
        $this->src = $src;
    }

    /**
     * Get Image alternative text \
     *  \
     * @return string $alt \
     */
    public function get_alt()
    {
        return $this->alt;
    }

    /**
     * Set Image alternative text \
     *  \
     * @param string $alt \
     */
    public function set_alt($alt)
    {
        $this->alt = $alt;
    }

    /**
     * Get Text on hover \
     *  \
     * @return string $title \
     */
    public function get_title()
    {
        return $this->title;
    }

    /**
     * Set Text on hover \
     *  \
     * @param string $title \
     */
    public function set_title($title)
    {
        $this->title = $title;
    }

    /**
     * Get Image scale \
     * Allow image to be scaled along with font size \
     * @return boolean $scale \
     */
    public function get_scale()
    {
        return $this->scale;
    }

    /**
     * Set Image scale \
     * Allow image to be scaled along with font size \
     * @param boolean $scale \
     */
    public function set_scale($scale)
    {
        $this->scale = $scale;
    }
    
    /**
    * Get Image width \
    *  \
    * @return string $width \
    */
    public function get_width() {
        return $this->width;
    }

    /**
    * Set Image width \
    *  \
    * @param string $width \
    */
    public function set_width ($width) {
        $this->width = $width;
    }
    
    /**
    * Get Image height \
    *  \
    * @return string $height \
    */
    public function get_height() {
        return $this->height;
    }

    /**
    * Set Image height \
    *  \
    * @param string $height \
    */
    public function set_height ($height) {
        $this->height = $height;
    }

}
