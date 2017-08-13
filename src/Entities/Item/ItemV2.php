<?php

namespace LearnosityQti\Entities\Item;

use LearnosityQti\Entities\BaseEntity;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.86.0","feedback":"v2.71.0","features":"v2.84.0"}
*/
class ItemV2 extends BaseEntity {
    protected $reference;
    protected $definition;
    protected $questions;
    protected $features;
    protected $metadata;
    protected $status;
    protected $description;
    protected $workflow;
    
    public function __construct(
                    $reference,
                                $definition
                        )
    {
                $this->reference = $reference;
                $this->definition = $definition;
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
    * Get definition \
    * An object defining the layout of the item wrapper. \
    * @return object $definition \
    */
    public function get_definition() {
        return $this->definition;
    }

    /**
    * Set definition \
    * An object defining the layout of the item wrapper. \
    * @param object $definition \
    */
    public function set_definition ($definition) {
        $this->definition = $definition;
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

