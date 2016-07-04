<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.86.0","feedback":"v2.71.0","features":"v2.84.0"}
 */
class choicematrix_ui_style extends BaseQuestionTypeAttribute
{
    protected $fontsize;
    protected $type;
    protected $stem_width;
    protected $option_width;
    protected $stem_title;
    protected $option_row_title;
    protected $horizontal_lines;
    protected $stem_numeration;

    public function __construct()
    {
    }

    /**
     * Get Font size \
     * Controls the size of base font for this question. Options are among 'small', 'normal', 'large', 'xlarge' and 'xxlarge'. \
     *
     * @return string $fontsize ie. small, normal, large, xlarge, xxlarge  \
     */
    public function get_fontsize()
    {
        return $this->fontsize;
    }

    /**
     * Set Font size \
     * Controls the size of base font for this question. Options are among 'small', 'normal', 'large', 'xlarge' and 'xxlarge'. \
     *
     * @param string $fontsize ie. small, normal, large, xlarge, xxlarge  \
     */
    public function set_fontsize($fontsize)
    {
        $this->fontsize = $fontsize;
    }

    /**
     * Get Layout \
     *  \
     *
     * @return string $type ie. table, inline  \
     */
    public function get_type()
    {
        return $this->type;
    }

    /**
     * Set Layout \
     *  \
     *
     * @param string $type ie. table, inline  \
     */
    public function set_type($type)
    {
        $this->type = $type;
    }

    /**
     * Get Stem Width \
     * The width of the stem column \
     *
     * @return string $stem_width \
     */
    public function get_stem_width()
    {
        return $this->stem_width;
    }

    /**
     * Set Stem Width \
     * The width of the stem column \
     *
     * @param string $stem_width \
     */
    public function set_stem_width($stem_width)
    {
        $this->stem_width = $stem_width;
    }

    /**
     * Get Option Width \
     * The width of each option column \
     *
     * @return string $option_width \
     */
    public function get_option_width()
    {
        return $this->option_width;
    }

    /**
     * Set Option Width \
     * The width of each option column \
     *
     * @param string $option_width \
     */
    public function set_option_width($option_width)
    {
        $this->option_width = $option_width;
    }

    /**
     * Get Stem Column Title \
     * The text appears above stem row \
     *
     * @return string $stem_title \
     */
    public function get_stem_title()
    {
        return $this->stem_title;
    }

    /**
     * Set Stem Column Title \
     * The text appears above stem row \
     *
     * @param string $stem_title \
     */
    public function set_stem_title($stem_title)
    {
        $this->stem_title = $stem_title;
    }

    /**
     * Get Option Row Title \
     * The text appears above option row \
     *
     * @return string $option_row_title \
     */
    public function get_option_row_title()
    {
        return $this->option_row_title;
    }

    /**
     * Set Option Row Title \
     * The text appears above option row \
     *
     * @param string $option_row_title \
     */
    public function set_option_row_title($option_row_title)
    {
        $this->option_row_title = $option_row_title;
    }

    /**
     * Get Show horizontal lines under stems \
     * Whether horizontal lines should be shown underneath each stem \
     *
     * @return boolean $horizontal_lines \
     */
    public function get_horizontal_lines()
    {
        return $this->horizontal_lines;
    }

    /**
     * Set Show horizontal lines under stems \
     * Whether horizontal lines should be shown underneath each stem \
     *
     * @param boolean $horizontal_lines \
     */
    public function set_horizontal_lines($horizontal_lines)
    {
        $this->horizontal_lines = $horizontal_lines;
    }

    /**
     * Get Stem Numeration \
     * Numeration character to be displayed to the right of the stem label. Possible values include "number", "upper-alpha", "l
     * ower-alpha" \
     *
     * @return string $stem_numeration ie. number, upper-alpha, lower-alpha  \
     */
    public function get_stem_numeration()
    {
        return $this->stem_numeration;
    }

    /**
     * Set Stem Numeration \
     * Numeration character to be displayed to the right of the stem label. Possible values include "number", "upper-alpha", "l
     * ower-alpha" \
     *
     * @param string $stem_numeration ie. number, upper-alpha, lower-alpha  \
     */
    public function set_stem_numeration($stem_numeration)
    {
        $this->stem_numeration = $stem_numeration;
    }


}

