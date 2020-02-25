<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class videoplayer extends BaseQuestionType {
    protected $type;
    protected $metadata;
    protected $simplefeature_id;
    protected $src;
    protected $player_type;
    protected $heading;
    protected $caption;
    
    public function __construct($type, $player_type, $src)
    {
                $this->type = $type;
                $this->player_type = $player_type;
                $this->src = $src;
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
    * Get src of the video \
    * @return type
    */
    public function get_src() {
        return $this->src;
    }
    /**
    * Set src of the video
    * @param string $src \
    */
    public function set_src() {
        $this->src = $src;
    }


    /**
    * Get Metadata \
    * Additional data for the video player \
    * @return videoplayer_metadata $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set Metadata \
    * Additional data for the video player \
    * @param videoplayer_metadata $metadata \
    */
    public function set_metadata (videoplayer_metadata $metadata) {
        $this->metadata = $metadata;
    }

    /**
    * Get Simple feature reference \
    *  \
    * @return string $simplefeature_id \
    */
    public function get_simplefeature_id() {
        return $this->simplefeature_id;
    }

    /**
    * Set Simple feature reference \
    *  \
    * @param string $simplefeature_id \
    */
    public function set_simplefeature_id ($simplefeature_id) {
        $this->simplefeature_id = $simplefeature_id;
    }

    /**
    * Get Video type \
    * Defines the type of video player you want to create. \
    * @return string $player_type \
    */
    public function get_player_type() {
        return $this->player_type;
    }

    /**
    * Set Video type \
    * Defines the type of video player you want to create. \
    * @param string $player_type \
    */
    public function set_player_type ($player_type) {
        $this->player_type = $player_type;
    }

    /**
    * Get Heading \
    * Heading of the video \
    * @return string $heading \
    */
    public function get_heading() {
        return $this->heading;
    }

    /**
    * Set Heading \
    * Heading of the video \
    * @param string $heading \
    */
    public function set_heading ($heading) {
        $this->heading = $heading;
    }

    /**
    * Get Caption \
    * Description of the video being played \
    * @return string $caption \
    */
    public function get_caption() {
        return $this->caption;
    }

    /**
    * Set Caption \
    * Description of the video being played \
    * @param string $caption \
    */
    public function set_caption ($caption) {
        $this->caption = $caption;
    }

    
    public function get_widget_type() {
    return 'feature';
    }
}

