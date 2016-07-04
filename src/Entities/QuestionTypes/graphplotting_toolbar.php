<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.86.0","feedback":"v2.71.0","features":"v2.84.0"}
 */
class graphplotting_toolbar extends BaseQuestionTypeAttribute
{
    protected $tools;
    protected $default_tool;
    protected $controls;

    public function __construct()
    {
    }

    /**
     * Get Tools \
     * An array containing either strings or a nested array of strings, defining the buttons or dropdown groups of tools that a
     * re available in the toolbar for this question. \
     *
     * @return array $tools \
     */
    public function get_tools()
    {
        return $this->tools;
    }

    /**
     * Set Tools \
     * An array containing either strings or a nested array of strings, defining the buttons or dropdown groups of tools that a
     * re available in the toolbar for this question. \
     *
     * @param array $tools \
     */
    public function set_tools(array $tools)
    {
        $this->tools = $tools;
    }

    /**
     * Get Default Tool \
     * The tool that will be set when the question is loaded. \
     *
     * @return string $default_tool \
     */
    public function get_default_tool()
    {
        return $this->default_tool;
    }

    /**
     * Set Default Tool \
     * The tool that will be set when the question is loaded. \
     *
     * @param string $default_tool \
     */
    public function set_default_tool($default_tool)
    {
        $this->default_tool = $default_tool;
    }

    /**
     * Get Controls \
     * Determines options a user has for controlling graph elements. \
     *
     * @return array $controls \
     */
    public function get_controls()
    {
        return $this->controls;
    }

    /**
     * Set Controls \
     * Determines options a user has for controlling graph elements. \
     *
     * @param array $controls \
     */
    public function set_controls(array $controls)
    {
        $this->controls = $controls;
    }


}

