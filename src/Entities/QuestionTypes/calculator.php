<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class calculator extends BaseQuestionType {
    protected $type;
    protected $metadata;
    protected $simplefeature_id;
    protected $mode;
    
    public function __construct(
                    $type,
                                $mode
                        )
    {
                $this->type = $type;
                $this->mode = $mode;
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
    * Get Mode \
    * Mode of calculator to render. Values: basic, scientific \
    * @return string $mode \
    */
    public function get_mode() {
        return $this->mode;
    }

    /**
    * Set Mode \
    * Mode of calculator to render. Values: basic, scientific \
    * @param string $mode \
    */
    public function set_mode ($mode) {
        $this->mode = $mode;
    }

    
    public function get_widget_type() {
    return 'feature';
    }
}

