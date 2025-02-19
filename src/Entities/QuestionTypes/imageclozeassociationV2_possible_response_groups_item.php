<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class imageclozeassociationV2_possible_response_groups_item extends BaseQuestionTypeAttribute {
    protected $title;
    protected $responses;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Title \
     *  \
     * @return string $title \
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * Set Title \
     *  \
     * @param string $title \
     */
    public function set_title ($title) {
        $this->title = $title;
    }

    /**
     * Get Possible responses \
     * Array of strings values that need to be dragged to the actual response position. \
     * @return array $responses \
     */
    public function get_responses() {
        return $this->responses;
    }

    /**
     * Set Possible responses \
     * Array of strings values that need to be dragged to the actual response position. \
     * @param array $responses \
     */
    public function set_responses (array $responses) {
        $this->responses = $responses;
    }

    
}

