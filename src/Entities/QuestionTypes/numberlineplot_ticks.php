<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.86.0","feedback":"v2.71.0","features":"v2.84.0"}
 */
class numberlineplot_ticks extends BaseQuestionTypeAttribute
{
    protected $distance;
    protected $minor_ticks;
    protected $base;
    protected $show;
    protected $show_min;
    protected $show_max;

    public function __construct()
    {
    }

    /**
     * Get Distance \
     * Distance between ticks on the number line \
     *
     * @return string/number $distance \
     */
    public function get_distance()
    {
        return $this->distance;
    }

    /**
     * Set Distance \
     * Distance between ticks on the number line \
     *
     * @param string /number $distance \
     */
    public function set_distance($distance)
    {
        $this->distance = $distance;
    }

    /**
     * Get Minor ticks \
     * Number of minor ticks between major ticks \
     *
     * @return number $minor_ticks \
     */
    public function get_minor_ticks()
    {
        return $this->minor_ticks;
    }

    /**
     * Set Minor ticks \
     * Number of minor ticks between major ticks \
     *
     * @param number $minor_ticks \
     */
    public function set_minor_ticks($minor_ticks)
    {
        $this->minor_ticks = $minor_ticks;
    }

    /**
     * Get Rendering base \
     * Value on the line, where rendering of ticks should start \
     *
     * @return string $base \
     */
    public function get_base()
    {
        return $this->base;
    }

    /**
     * Set Rendering base \
     * Value on the line, where rendering of ticks should start \
     *
     * @param string $base \
     */
    public function set_base($base)
    {
        $this->base = $base;
    }

    /**
     * Get Show \
     * Whether to draw ticks on the line or not \
     *
     * @return boolean $show \
     */
    public function get_show()
    {
        return $this->show;
    }

    /**
     * Set Show \
     * Whether to draw ticks on the line or not \
     *
     * @param boolean $show \
     */
    public function set_show($show)
    {
        $this->show = $show;
    }

    /**
     * Get Show min \
     * Whether to draw min tick on the line or not \
     *
     * @return boolean $show_min \
     */
    public function get_show_min()
    {
        return $this->show_min;
    }

    /**
     * Set Show min \
     * Whether to draw min tick on the line or not \
     *
     * @param boolean $show_min \
     */
    public function set_show_min($show_min)
    {
        $this->show_min = $show_min;
    }

    /**
     * Get Show max \
     * Whether to draw max tick on the line or not \
     *
     * @return boolean $show_max \
     */
    public function get_show_max()
    {
        return $this->show_max;
    }

    /**
     * Set Show max \
     * Whether to draw max tick on the line or not \
     *
     * @param boolean $show_max \
     */
    public function set_show_max($show_max)
    {
        $this->show_max = $show_max;
    }


}

