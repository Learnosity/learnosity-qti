<?php

namespace Learnosity\Entities\Activity;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.68.0","feedback":"v2.35.0","features":"v2.68.0"}
*/
class activity_data_config_time extends BaseQuestionTypeAttribute {
    protected $max_time;
    protected $limit_type;
    protected $show_pause;
    protected $show_time;
    protected $countdown;
    
    public function __construct(
            )
    {
            }

    /**
    * Get max_time \
    *  \
    * @return string $max_time \
    */
    public function get_max_time() {
        return $this->max_time;
    }

    /**
    * Set max_time \
    *  \
    * @param string $max_time \
    */
    public function set_max_time ($max_time) {
        $this->max_time = $max_time;
    }

    /**
    * Get limit_type \
    *  \
    * @return string $limit_type \
    */
    public function get_limit_type() {
        return $this->limit_type;
    }

    /**
    * Set limit_type \
    *  \
    * @param string $limit_type \
    */
    public function set_limit_type ($limit_type) {
        $this->limit_type = $limit_type;
    }

    /**
    * Get show_pause \
    *  \
    * @return string $show_pause \
    */
    public function get_show_pause() {
        return $this->show_pause;
    }

    /**
    * Set show_pause \
    *  \
    * @param string $show_pause \
    */
    public function set_show_pause ($show_pause) {
        $this->show_pause = $show_pause;
    }

    /**
    * Get show_time \
    *  \
    * @return string $show_time \
    */
    public function get_show_time() {
        return $this->show_time;
    }

    /**
    * Set show_time \
    *  \
    * @param string $show_time \
    */
    public function set_show_time ($show_time) {
        $this->show_time = $show_time;
    }

    /**
    * Get countdown \
    *  \
    * @return string $countdown \
    */
    public function get_countdown() {
        return $this->countdown;
    }

    /**
    * Set countdown \
    *  \
    * @param string $countdown \
    */
    public function set_countdown ($countdown) {
        $this->countdown = $countdown;
    }

    
}

