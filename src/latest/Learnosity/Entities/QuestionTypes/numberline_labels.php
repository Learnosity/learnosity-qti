<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
 */
class numberline_labels extends BaseQuestionTypeAttribute
{
    protected $frequency;
    protected $points;
    protected $show_min;
    protected $show_max;

    public function __construct()
    {
    }

    /**
     * Get Frequency \
     * Frequency with which to draw labels \
     *
     * @return number $frequency \
     */
    public function get_frequency()
    {
        return $this->frequency;
    }

    /**
     * Set Frequency \
     * Frequency with which to draw labels \
     *
     * @param number $frequency \
     */
    public function set_frequency($frequency)
    {
        $this->frequency = $frequency;
    }

    /**
     * Get Points \
     * Specific point at which labels need to be drawn. Separate values by commas, eg: -2.5, 2.5 \
     *
     * @return string $points \
     */
    public function get_points()
    {
        return $this->points;
    }

    /**
     * Set Points \
     * Specific point at which labels need to be drawn. Separate values by commas, eg: -2.5, 2.5 \
     *
     * @param string $points \
     */
    public function set_points($points)
    {
        $this->points = $points;
    }

    /**
     * Get Show min \
     * Whether to draw a label on the min value of the number line \
     *
     * @return boolean $show_min \
     */
    public function get_show_min()
    {
        return $this->show_min;
    }

    /**
     * Set Show min \
     * Whether to draw a label on the min value of the number line \
     *
     * @param boolean $show_min \
     */
    public function set_show_min($show_min)
    {
        $this->show_min = $show_min;
    }

    /**
     * Get Show max \
     * Whether to draw a label on the max value of the number line \
     *
     * @return boolean $show_max \
     */
    public function get_show_max()
    {
        return $this->show_max;
    }

    /**
     * Set Show max \
     * Whether to draw a label on the max value of the number line \
     *
     * @param boolean $show_max \
     */
    public function set_show_max($show_max)
    {
        $this->show_max = $show_max;
    }


}

