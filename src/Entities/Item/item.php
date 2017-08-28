<?php

namespace LearnosityQti\Entities\Item;

use LearnosityQti\Entities\BaseEntity;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class item extends BaseEntity {
    protected $reference;
    protected $content;
    protected $questionReferences;
    protected $questions;
    protected $features;
    protected $metadata;
    protected $status;
    protected $description;
    protected $workflow;
    
    public function __construct(
                    $reference
                        )
    {
                $this->reference = $reference;
            }

    /**
    * Get reference \
    * A unique item identifier across Itembank. It has a limitation of maximum of 150 characters and must only contain ASCII p
	rintable characters, except for double quotes, single quotes and accent. \
    * @return string $reference \
    */
    public function get_reference() {
        return $this->reference;
    }

    /**
    * Set reference \
    * A unique item identifier across Itembank. It has a limitation of maximum of 150 characters and must only contain ASCII p
	rintable characters, except for double quotes, single quotes and accent. \
    * @param string $reference \
    */
    public function set_reference ($reference) {
        $this->reference = $reference;
    }

    /**
    * Get content \
    *  \
    * @return string $content \
    */
    public function get_content() {
        return $this->content;
    }

    /**
    * Set content \
    *  \
    * @param string $content \
    */
    public function set_content ($content) {
        $this->content = $content;
    }

    /**
    * Get questionReferences \
    *  \
    * @return array $questionReferences \
    */
    public function get_questionReferences() {
        return $this->questionReferences;
    }

    /**
    * Set questionReferences \
    *  \
    * @param array $questionReferences \
    */
    public function set_questionReferences (array $questionReferences) {
        $this->questionReferences = $questionReferences;
    }

    /**
    * Get questions \
    *  \
    * @return array $questions \
    */
    public function get_questions() {
        return $this->questions;
    }

    /**
    * Set questions \
    *  \
    * @param array $questions \
    */
    public function set_questions (array $questions) {
        $this->questions = $questions;
    }

    /**
    * Get features \
    *  \
    * @return array $features \
    */
    public function get_features() {
        return $this->features;
    }

    /**
    * Set features \
    *  \
    * @param array $features \
    */
    public function set_features (array $features) {
        $this->features = $features;
    }

    /**
    * Get metadata \
    * An object containing optional metadata like `scoring_type`. \
    * @return object $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set metadata \
    * An object containing optional metadata like `scoring_type`. \
    * @param object $metadata \
    */
    public function set_metadata ($metadata) {
        $this->metadata = $metadata;
    }

    /**
    * Get status \
    * ie. `published`, `unpublished`, and `archived` \
    * @return string $status ie. unpublished, published, archived  \
    */
    public function get_status() {
        return $this->status;
    }

    /**
    * Set status \
    * ie. `published`, `unpublished`, and `archived` \
    * @param string $status ie. unpublished, published, archived  \
    */
    public function set_status ($status) {
        $this->status = $status;
    }

    /**
    * Get description \
    *  \
    * @return string $description \
    */
    public function get_description() {
        return $this->description;
    }

    /**
    * Set description \
    *  \
    * @param string $description \
    */
    public function set_description ($description) {
        $this->description = $description;
    }

    /**
    * Get workflow \
    *  \
    * @return array $workflow \
    */
    public function get_workflow() {
        return $this->workflow;
    }

    /**
    * Set workflow \
    *  \
    * @param array $workflow \
    */
    public function set_workflow (array $workflow) {
        $this->workflow = $workflow;
    }

    
}

