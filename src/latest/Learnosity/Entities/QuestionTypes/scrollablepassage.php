<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
 */
class scrollablepassage extends BaseQuestionType
{
    protected $data-type;
    protected $data-width;
    protected $data-height;
    protected $data-border;
    protected $data-subtract-height;
    
    public function __construct()
    {
    }

    /**
     * Get data-type \
     * Use the value 'scrollablepassage' for this field. \
     *
     * @return string $data-type \
     */
    public function get_data-type() {
        return $this->data-type;
    }

/**
 * Set data-type \
 * Use the value 'scrollablepassage' for this field. \
 *
 * @param string $data -type \
 */
public
function set_data-type($data - type){
$this->data - type = $data - type;
    }

    /**
     * Get data-width \
     * Width of the scrollable passage container in pixels or percentage. \
     *
     * @return string $data-width \
     */
    public function get_data-width(){
        return $this->data - width;
    }

    /**
     * Set data-width \
     * Width of the scrollable passage container in pixels or percentage. \
     *
     * @param string $data -width \
     */
    public function set_data-width($data - width){
$this->data - width = $data - width;
    }

    /**
     * Get data-height \
     * Height of the scrollable passage container in pixels or percentage. \
     *
     * @return string $data-height \
     */
    public function get_data-height(){
        return $this->data - height;
    }

    /**
     * Set data-height \
     * Height of the scrollable passage container in pixels or percentage. \
     *
     * @param string $data -height \
     */
    public function set_data-height($data - height){
$this->data - height = $data - height;
    }

    /**
     * Get data-border \
     * Whether to display a wrapper around the scrollable passage container. \
     *
     * @return boolean $data-border \
     */
    public function get_data-border(){
        return $this->data - border;
    }

    /**
     * Set data-border \
     * Whether to display a wrapper around the scrollable passage container. \
     *
     * @param boolean $data -border \
     */
    public function set_data-border($data - border){
$this->data - border = $data - border;
    }

    /**
     * Get data-subtract-height \
     * A value which reduces the height of the passage container. \
     *
     * @return string $data-subtract-height \
     */
    public function get_data-subtract - height(){
        return $this->data - subtract - height;
    }

    /**
     * Set data-subtract-height \
     * A value which reduces the height of the passage container. \
     *
     * @param string $data -subtract-height \
     */
    public function set_data-subtract - height($data - subtract - height){
$this->data - subtract - height = $data - subtract - height;
    }

    
    public function get_widget_type()
{
    return 'feature';
}
}

