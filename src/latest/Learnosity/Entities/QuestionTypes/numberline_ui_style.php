<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
 */
class numberline_ui_style extends BaseQuestionTypeAttribute
{
    protected $fontsize;
    protected $height;
    protected $width;
    protected $number_line_margin;
    protected $points_distance_x;
    protected $points_distance_y;
    protected $line_position;
    protected $title_position;
    protected $points_box_position;

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
     * Get Height \
     * The height of the drawn area with trailing px unit eg. "250px". If undefined or empty, the board's height will scaled au
     * tomatically based on the contents \
     *
     * @return string $height \
     */
    public function get_height()
    {
        return $this->height;
    }

    /**
     * Set Height \
     * The height of the drawn area with trailing px unit eg. "250px". If undefined or empty, the board's height will scaled au
     * tomatically based on the contents \
     *
     * @param string $height \
     */
    public function set_height($height)
    {
        $this->height = $height;
    }

    /**
     * Get Width \
     * The width of the drawn area with trailing px unit eg. "550px". \
     *
     * @return string $width \
     */
    public function get_width()
    {
        return $this->width;
    }

    /**
     * Set Width \
     * The width of the drawn area with trailing px unit eg. "550px". \
     *
     * @param string $width \
     */
    public function set_width($width)
    {
        $this->width = $width;
    }

    /**
     * Get Number line margin \
     * The distance from the number line's extremes to the sides with trailing px unit eg. "5px" \
     *
     * @return string $number_line_margin \
     */
    public function get_number_line_margin()
    {
        return $this->number_line_margin;
    }

    /**
     * Set Number line margin \
     * The distance from the number line's extremes to the sides with trailing px unit eg. "5px" \
     *
     * @param string $number_line_margin \
     */
    public function set_number_line_margin($number_line_margin)
    {
        $this->number_line_margin = $number_line_margin;
    }

    /**
     * Get Points distance x \
     * The distance in X axis between two points with trailing px unit eg. "10px" \
     *
     * @return string $points_distance_x \
     */
    public function get_points_distance_x()
    {
        return $this->points_distance_x;
    }

    /**
     * Set Points distance x \
     * The distance in X axis between two points with trailing px unit eg. "10px" \
     *
     * @param string $points_distance_x \
     */
    public function set_points_distance_x($points_distance_x)
    {
        $this->points_distance_x = $points_distance_x;
    }

    /**
     * Get Points distance y \
     * The distance in Y axis between two points with trailing px unit eg. "20px" \
     *
     * @return string $points_distance_y \
     */
    public function get_points_distance_y()
    {
        return $this->points_distance_y;
    }

    /**
     * Set Points distance y \
     * The distance in Y axis between two points with trailing px unit eg. "20px" \
     *
     * @param string $points_distance_y \
     */
    public function set_points_distance_y($points_distance_y)
    {
        $this->points_distance_y = $points_distance_y;
    }

    /**
     * Get Line position \
     * At which percentage of the height should the Number Line appear \
     *
     * @return number $line_position \
     */
    public function get_line_position()
    {
        return $this->line_position;
    }

    /**
     * Set Line position \
     * At which percentage of the height should the Number Line appear \
     *
     * @param number $line_position \
     */
    public function set_line_position($line_position)
    {
        $this->line_position = $line_position;
    }

    /**
     * Get Title position \
     * At which percentage of the height should the Line's Title appear \
     *
     * @return number $title_position \
     */
    public function get_title_position()
    {
        return $this->title_position;
    }

    /**
     * Set Title position \
     * At which percentage of the height should the Line's Title appear \
     *
     * @param number $title_position \
     */
    public function set_title_position($title_position)
    {
        $this->title_position = $title_position;
    }

    /**
     * Get Points box position \
     * At which percentage of the height should the Points box container start \
     *
     * @return number $points_box_position \
     */
    public function get_points_box_position()
    {
        return $this->points_box_position;
    }

    /**
     * Set Points box position \
     * At which percentage of the height should the Points box container start \
     *
     * @param number $points_box_position \
     */
    public function set_points_box_position($points_box_position)
    {
        $this->points_box_position = $points_box_position;
    }


}

