<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
 */
class numberline_ticks extends BaseQuestionTypeAttribute
{
    protected $distance;
    protected $fractions;
    protected $base;
    protected $show;

    public function __construct()
    {
    }

    /**
     * Get Distance \
     * The distance between ticks on the number line \
     *
     * @return string/number $distance \
     */
    public function get_distance()
    {
        return $this->distance;
    }

    /**
     * Set Distance \
     * The distance between ticks on the number line \
     *
     * @param string /number $distance \
     */
    public function set_distance($distance)
    {
        $this->distance = $distance;
    }

    /**
     * Get Fractions format \
     * <p style='text-align:left'>If the distance is set to a fraction this will determine in which format the fractions will b
     * e rendered on the Number Line. Select to display only:
     * <ul style='text-align:lef
     * t;padding:0px 0px 0px 15px;'>
     * <li>Not normalized and mixed fractions.</li>
     *
     * <li>Normalized and mixed fractions.</li>
     *
     * <li>Improper fractions.</li>
     * </ul>
     * <
     * /p> \
     *
     * @return string $fractions \
     */
    public function get_fractions()
    {
        return $this->fractions;
    }

    /**
     * Set Fractions format \
     * <p style='text-align:left'>If the distance is set to a fraction this will determine in which format the fractions will b
     * e rendered on the Number Line. Select to display only:
     * <ul style='text-align:lef
     * t;padding:0px 0px 0px 15px;'>
     * <li>Not normalized and mixed fractions.</li>
     *
     * <li>Normalized and mixed fractions.</li>
     *
     * <li>Improper fractions.</li>
     * </ul>
     * <
     * /p> \
     *
     * @param string $fractions \
     */
    public function set_fractions($fractions)
    {
        $this->fractions = $fractions;
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


}

