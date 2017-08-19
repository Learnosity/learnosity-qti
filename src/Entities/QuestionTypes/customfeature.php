<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class customfeature extends BaseQuestionType {
    protected $type;
    protected $metadata;
    protected $custom_type;
    protected $js;
    protected $css;
    protected $version;
    
    public function __construct(
                    $type,
                                $custom_type,
                                $js,
                                $version
                        )
    {
                $this->type = $type;
                $this->custom_type = $custom_type;
                $this->js = $js;
                $this->version = $version;
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
    * Get Custom type \
    * A key that identifies this custom feature type. \
    * @return string $custom_type \
    */
    public function get_custom_type() {
        return $this->custom_type;
    }

    /**
    * Set Custom type \
    * A key that identifies this custom feature type. \
    * @param string $custom_type \
    */
    public function set_custom_type ($custom_type) {
        $this->custom_type = $custom_type;
    }

    /**
    * Get JavaScript file \
    * A URL to a JavaScript file which defines an AMD module for the feature. See this <a href="//docs.learnosity.com/question
	sapi/knowledgebase/customfeatures.php">knowledgebase article</a> for more information. \
    * @return string $js \
    */
    public function get_js() {
        return $this->js;
    }

    /**
    * Set JavaScript file \
    * A URL to a JavaScript file which defines an AMD module for the feature. See this <a href="//docs.learnosity.com/question
	sapi/knowledgebase/customfeatures.php">knowledgebase article</a> for more information. \
    * @param string $js \
    */
    public function set_js ($js) {
        $this->js = $js;
    }

    /**
    * Get CSS file \
    * A URL to a CSS file containing styles for the feature. \
    * @return string $css \
    */
    public function get_css() {
        return $this->css;
    }

    /**
    * Set CSS file \
    * A URL to a CSS file containing styles for the feature. \
    * @param string $css \
    */
    public function set_css ($css) {
        $this->css = $css;
    }

    /**
    * Get Version \
    * A number that identifies the version of the feature e.g. v0.1.0. \
    * @return string $version \
    */
    public function get_version() {
        return $this->version;
    }

    /**
    * Set Version \
    * A number that identifies the version of the feature e.g. v0.1.0. \
    * @param string $version \
    */
    public function set_version ($version) {
        $this->version = $version;
    }

    
    public function get_widget_type() {
    return 'feature';
    }
}

