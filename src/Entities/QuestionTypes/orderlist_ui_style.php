<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.86.0","feedback":"v2.71.0","features":"v2.84.0"}
 */
class orderlist_ui_style extends BaseQuestionTypeAttribute
{
    protected $fontsize;
    protected $validation_stem_numeration;
    protected $type;
    protected $show_drag_handle;

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
     * Get Validation Stem Numeration \
     * Numeration character to be displayed to the left of the validation label. \
     *
     * @return string $validation_stem_numeration ie. number, upper-alpha, lower-alpha  \
     */
    public function get_validation_stem_numeration()
    {
        return $this->validation_stem_numeration;
    }

    /**
     * Set Validation Stem Numeration \
     * Numeration character to be displayed to the left of the validation label. \
     *
     * @param string $validation_stem_numeration ie. number, upper-alpha, lower-alpha  \
     */
    public function set_validation_stem_numeration($validation_stem_numeration)
    {
        $this->validation_stem_numeration = $validation_stem_numeration;
    }

    /**
     * Get Layout \
     * The style of the list for the user interface. Supported types are 'button', 'list', and 'inline'. \
     *
     * @return string $type \
     */
    public function get_type()
    {
        return $this->type;
    }

    /**
     * Set Layout \
     * The style of the list for the user interface. Supported types are 'button', 'list', and 'inline'. \
     *
     * @param string $type \
     */
    public function set_type($type)
    {
        $this->type = $type;
    }

    /**
     * Get Show drag handle \
     * Determines whether to show the drag handle. \
     *
     * @return boolean $show_drag_handle \
     */
    public function get_show_drag_handle()
    {
        return $this->show_drag_handle;
    }

    /**
     * Set Show drag handle \
     * Determines whether to show the drag handle. \
     *
     * @param boolean $show_drag_handle \
     */
    public function set_show_drag_handle($show_drag_handle)
    {
        $this->show_drag_handle = $show_drag_handle;
    }


}

