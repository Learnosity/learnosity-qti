<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
 */
class numberlineplot_line extends BaseQuestionTypeAttribute
{
    protected $min;
    protected $left_arrow;
    protected $max;
    protected $right_arrow;

    public function __construct()
    {
    }

    /**
     * Get Min \
     * Minimum value for the number line \
     *
     * @return number $min \
     */
    public function get_min()
    {
        return $this->min;
    }

    /**
     * Set Min \
     * Minimum value for the number line \
     *
     * @param number $min \
     */
    public function set_min($min)
    {
        $this->min = $min;
    }

    /**
     * Get Show min arrow \
     * Draw an arrow on the left hand side of the line \
     *
     * @return boolean $left_arrow \
     */
    public function get_left_arrow()
    {
        return $this->left_arrow;
    }

    /**
     * Set Show min arrow \
     * Draw an arrow on the left hand side of the line \
     *
     * @param boolean $left_arrow \
     */
    public function set_left_arrow($left_arrow)
    {
        $this->left_arrow = $left_arrow;
    }

    /**
     * Get Max \
     * Maximum value for the number line \
     *
     * @return number $max \
     */
    public function get_max()
    {
        return $this->max;
    }

    /**
     * Set Max \
     * Maximum value for the number line \
     *
     * @param number $max \
     */
    public function set_max($max)
    {
        $this->max = $max;
    }

    /**
     * Get Show max arrow \
     * Draw an arrow on the right hand side of the line \
     *
     * @return boolean $right_arrow \
     */
    public function get_right_arrow()
    {
        return $this->right_arrow;
    }

    /**
     * Set Show max arrow \
     * Draw an arrow on the right hand side of the line \
     *
     * @param boolean $right_arrow \
     */
    public function set_right_arrow($right_arrow)
    {
        $this->right_arrow = $right_arrow;
    }


}

