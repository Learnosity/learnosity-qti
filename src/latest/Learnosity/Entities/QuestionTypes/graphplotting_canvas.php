<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
 */
class graphplotting_canvas extends BaseQuestionTypeAttribute
{
    protected $snap_to;
    protected $show_hover_position;
    protected $x_min;
    protected $x_max;
    protected $y_min;
    protected $y_max;

    public function __construct()
    {
    }

    /**
     * Get Snap to \
     * Defines the snap applied on points, possible values are: "grid", "ticks" or a numeric value \
     *
     * @return string/number $snap_to \
     */
    public function get_snap_to()
    {
        return $this->snap_to;
    }

    /**
     * Set Snap to \
     * Defines the snap applied on points, possible values are: "grid", "ticks" or a numeric value \
     *
     * @param string /number $snap_to \
     */
    public function set_snap_to($snap_to)
    {
        $this->snap_to = $snap_to;
    }

    /**
     * Get Show hover position \
     * Defines if the position of a point should be displayed when hovering over or dragging it \
     *
     * @return boolean $show_hover_position \
     */
    public function get_show_hover_position()
    {
        return $this->show_hover_position;
    }

    /**
     * Set Show hover position \
     * Defines if the position of a point should be displayed when hovering over or dragging it \
     *
     * @param boolean $show_hover_position \
     */
    public function set_show_hover_position($show_hover_position)
    {
        $this->show_hover_position = $show_hover_position;
    }

    /**
     * Get X min \
     * X axis lower value \
     *
     * @return number $x_min \
     */
    public function get_x_min()
    {
        return $this->x_min;
    }

    /**
     * Set X min \
     * X axis lower value \
     *
     * @param number $x_min \
     */
    public function set_x_min($x_min)
    {
        $this->x_min = $x_min;
    }

    /**
     * Get X max \
     * X axis higher value \
     *
     * @return number $x_max \
     */
    public function get_x_max()
    {
        return $this->x_max;
    }

    /**
     * Set X max \
     * X axis higher value \
     *
     * @param number $x_max \
     */
    public function set_x_max($x_max)
    {
        $this->x_max = $x_max;
    }

    /**
     * Get Y min \
     * Y axis lower value \
     *
     * @return number $y_min \
     */
    public function get_y_min()
    {
        return $this->y_min;
    }

    /**
     * Set Y min \
     * Y axis lower value \
     *
     * @param number $y_min \
     */
    public function set_y_min($y_min)
    {
        $this->y_min = $y_min;
    }

    /**
     * Get Y max \
     * Y axis higher value \
     *
     * @return number $y_max \
     */
    public function get_y_max()
    {
        return $this->y_max;
    }

    /**
     * Set Y max \
     * Y axis higher value \
     *
     * @param number $y_max \
     */
    public function set_y_max($y_max)
    {
        $this->y_max = $y_max;
    }


}

