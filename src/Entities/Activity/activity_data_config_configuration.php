<?php

namespace LearnosityQti\Entities\Activity;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.84.0","feedback":"v2.71.0","features":"v2.84.0"}
*/
class activity_data_config_configuration extends BaseQuestionTypeAttribute {
    protected $fontsize;
    protected $dynamic;
    protected $idle_timeout;
    protected $events;
    protected $preload_audio_player;
    protected $submit_criteria;
    
    public function __construct(
            )
    {
            }

    /**
    * Get fontsize \
    *  \
    * @return string $fontsize \
    */
    public function get_fontsize() {
        return $this->fontsize;
    }

    /**
    * Set fontsize \
    *  \
    * @param string $fontsize \
    */
    public function set_fontsize ($fontsize) {
        $this->fontsize = $fontsize;
    }

    /**
    * Get dynamic \
    *  \
    * @return boolean $dynamic \
    */
    public function get_dynamic() {
        return $this->dynamic;
    }

    /**
    * Set dynamic \
    *  \
    * @param boolean $dynamic \
    */
    public function set_dynamic ($dynamic) {
        $this->dynamic = $dynamic;
    }

    /**
    * Get idle_timeout \
    *  \
    * @return boolean $idle_timeout \
    */
    public function get_idle_timeout() {
        return $this->idle_timeout;
    }

    /**
    * Set idle_timeout \
    *  \
    * @param boolean $idle_timeout \
    */
    public function set_idle_timeout ($idle_timeout) {
        $this->idle_timeout = $idle_timeout;
    }

    /**
    * Get events \
    *  \
    * @return boolean $events \
    */
    public function get_events() {
        return $this->events;
    }

    /**
    * Set events \
    *  \
    * @param boolean $events \
    */
    public function set_events ($events) {
        $this->events = $events;
    }

    /**
    * Get preload_audio_player \
    *  \
    * @return boolean $preload_audio_player \
    */
    public function get_preload_audio_player() {
        return $this->preload_audio_player;
    }

    /**
    * Set preload_audio_player \
    *  \
    * @param boolean $preload_audio_player \
    */
    public function set_preload_audio_player ($preload_audio_player) {
        $this->preload_audio_player = $preload_audio_player;
    }

    /**
    * Get submit_criteria \
    *  \
    * @return boolean $submit_criteria \
    */
    public function get_submit_criteria() {
        return $this->submit_criteria;
    }

    /**
    * Set submit_criteria \
    *  \
    * @param boolean $submit_criteria \
    */
    public function set_submit_criteria ($submit_criteria) {
        $this->submit_criteria = $submit_criteria;
    }

    
}

