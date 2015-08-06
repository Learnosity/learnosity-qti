<?php

namespace Learnosity\Entities\Item;

use Learnosity\Entities\BaseEntity;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.68.0","feedback":"v2.35.0","features":"v2.68.0"}
*/
class item extends BaseEntity {
    protected $reference;
    protected $questionReferences;
    protected $content;
    protected $status;
    protected $description;
    protected $metadata;
    protected $workflow;
    
    public function __construct(
                    $reference,
                                array $questionReferences,
                                $content
                        )
    {
                $this->reference = $reference;
                $this->questionReferences = $questionReferences;
                $this->content = $content;
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
    * Get questionReferences \
    * An array of question references. Like item reference, it has a limitation of maximum of 150 characters and must only con
	tain ASCII printable characters, except for double quotes, single quotes and accent. ie. `questionReferenceOne` \
    * @return array $questionReferences \
    */
    public function get_questionReferences() {
        return $this->questionReferences;
    }

    /**
    * Set questionReferences \
    * An array of question references. Like item reference, it has a limitation of maximum of 150 characters and must only con
	tain ASCII printable characters, except for double quotes, single quotes and accent. ie. `questionReferenceOne` \
    * @param array $questionReferences \
    */
    public function set_questionReferences (array $questionReferences) {
        $this->questionReferences = $questionReferences;
    }

    /**
    * Get content \
    * A valid HTML string that may contains the structure that holds Questions. It shall include questions placeholder set in 
	`questionReferences`, ie. <span class="learnosity-response question-questionReferenceOne"></span> \
    * @return string $content \
    */
    public function get_content() {
        return $this->content;
    }

    /**
    * Set content \
    * A valid HTML string that may contains the structure that holds Questions. It shall include questions placeholder set in 
	`questionReferences`, ie. <span class="learnosity-response question-questionReferenceOne"></span> \
    * @param string $content \
    */
    public function set_content ($content) {
        $this->content = $content;
    }

    /**
    * Get status \
    * ie. `published`, `unpublished`, and `deleted` \
    * @return string $status ie. unpublished, published, deleted  \
    */
    public function get_status() {
        return $this->status;
    }

    /**
    * Set status \
    * ie. `published`, `unpublished`, and `deleted` \
    * @param string $status ie. unpublished, published, deleted  \
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
    * Get metadata \
    *  \
    * @return array $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set metadata \
    *  \
    * @param array $metadata \
    */
    public function set_metadata (array $metadata) {
        $this->metadata = $metadata;
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

