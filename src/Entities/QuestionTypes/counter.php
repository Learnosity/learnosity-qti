<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class counter extends BaseQuestionType {
    protected $type;
    protected $metadata;
    protected $length;
    protected $direction;
    protected $title;
    protected $template;
    protected $show_minutes;
    
    public function __construct(
                    $type,
                                $length
                        )
    {
                $this->type = $type;
                $this->length = $length;
            }

    /**
    * Get Feature Type \
    *  \
    * @return string $type \
    */
    public function get_type() {
        return $this->type;
    }

    /**
    * Set Feature Type \
    *  \
    * @param string $type \
    */
    public function set_type ($type) {
        $this->type = $type;
    }

    /**
    * Get metadata \
    *  \
    * @return object $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set metadata \
    *  \
    * @param object $metadata \
    */
    public function set_metadata ($metadata) {
        $this->metadata = $metadata;
    }

    /**
    * Get Length \
    * Counter length in seconds \
    * @return number $length \
    */
    public function get_length() {
        return $this->length;
    }

    /**
    * Set Length \
    * Counter length in seconds \
    * @param number $length \
    */
    public function set_length ($length) {
        $this->length = $length;
    }

    /**
    * Get Direction \
    * Direction for the counter to count, options: 'up', 'down' \
    * @return string $direction \
    */
    public function get_direction() {
        return $this->direction;
    }

    /**
    * Set Direction \
    * Direction for the counter to count, options: 'up', 'down' \
    * @param string $direction \
    */
    public function set_direction ($direction) {
        $this->direction = $direction;
    }

    /**
    * Get Title \
    * Title visible on the counter UI \
    * @return string $title \
    */
    public function get_title() {
        return $this->title;
    }

    /**
    * Set Title \
    * Title visible on the counter UI \
    * @param string $title \
    */
    public function set_title ($title) {
        $this->title = $title;
    }

    /**
    * Get Template \
    * Counter text content, {num} denotes the location of the counter number \
    * @return string $template \
    */
    public function get_template() {
        return $this->template;
    }

    /**
    * Set Template \
    * Counter text content, {num} denotes the location of the counter number \
    * @param string $template \
    */
    public function set_template ($template) {
        $this->template = $template;
    }

    /**
    * Get Show Minutes? \
    * Determines the display format for the counter. If true then the format is mm:ss, if false only the seconds are displayed
	. \
    * @return boolean $show_minutes \
    */
    public function get_show_minutes() {
        return $this->show_minutes;
    }

    /**
    * Set Show Minutes? \
    * Determines the display format for the counter. If true then the format is mm:ss, if false only the seconds are displayed
	. \
    * @param boolean $show_minutes \
    */
    public function set_show_minutes ($show_minutes) {
        $this->show_minutes = $show_minutes;
    }

    
    public function get_widget_type() {
    return 'feature';
    }
}

