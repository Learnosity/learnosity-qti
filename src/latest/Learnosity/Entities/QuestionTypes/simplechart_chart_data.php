<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
 */
class simplechart_chart_data extends BaseQuestionTypeAttribute
{
    protected $name;
    protected $data;

    public function __construct()
    {
    }

    /**
     * Get Name \
     * Chart title \
     *
     * @return string $name \
     */
    public function get_name()
    {
        return $this->name;
    }

    /**
     * Set Name \
     * Chart title \
     *
     * @param string $name \
     */
    public function set_name($name)
    {
        $this->name = $name;
    }

    /**
     * Get Data \
     * Chart data \
     *
     * @return array $data \
     */
    public function get_data()
    {
        return $this->data;
    }

    /**
     * Set Data \
     * Chart data \
     *
     * @param array $data \
     */
    public function set_data(array $data)
    {
        $this->data = $data;
    }


}

