<?php

namespace LearnosityQti\Entities\Activity;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.86.0","feedback":"v2.71.0","features":"v2.84.0"}
 */
class activity_data_config_navigation_show_accessibility extends BaseQuestionTypeAttribute
{
    protected $show_colourscheme;
    protected $show_fontsize;
    protected $show_zoom;

    public function __construct()
    {
    }

    /**
     * Get show_colourscheme \
     *  \
     *
     * @return boolean $show_colourscheme \
     */
    public function get_show_colourscheme()
    {
        return $this->show_colourscheme;
    }

    /**
     * Set show_colourscheme \
     *  \
     *
     * @param boolean $show_colourscheme \
     */
    public function set_show_colourscheme($show_colourscheme)
    {
        $this->show_colourscheme = $show_colourscheme;
    }

    /**
     * Get show_fontsize \
     *  \
     *
     * @return boolean $show_fontsize \
     */
    public function get_show_fontsize()
    {
        return $this->show_fontsize;
    }

    /**
     * Set show_fontsize \
     *  \
     *
     * @param boolean $show_fontsize \
     */
    public function set_show_fontsize($show_fontsize)
    {
        $this->show_fontsize = $show_fontsize;
    }

    /**
     * Get show_zoom \
     *  \
     *
     * @return boolean $show_zoom \
     */
    public function get_show_zoom()
    {
        return $this->show_zoom;
    }

    /**
     * Set show_zoom \
     *  \
     *
     * @param boolean $show_zoom \
     */
    public function set_show_zoom($show_zoom)
    {
        $this->show_zoom = $show_zoom;
    }


}

