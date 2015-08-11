<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.68.0","feedback":"v2.35.0","features":"v2.68.0"}
*/
class sharedpassage extends BaseQuestionType {
    protected $type;
    protected $metadata;
    protected $heading;
    protected $content;
    protected $is_math;
    protected $math_renderer;
    
    public function __construct(
                    $type,
                                $content
                        )
    {
                $this->type = $type;
                $this->content = $content;
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
    * Get Metadata \
    * Additional data for the shared passage \
    * @return sharedpassage_metadata $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set Metadata \
    * Additional data for the shared passage \
    * @param sharedpassage_metadata $metadata \
    */
    public function set_metadata (sharedpassage_metadata $metadata) {
        $this->metadata = $metadata;
    }

    /**
    * Get Heading \
    * Heading of the shared passage \
    * @return string $heading \
    */
    public function get_heading() {
        return $this->heading;
    }

    /**
    * Set Heading \
    * Heading of the shared passage \
    * @param string $heading \
    */
    public function set_heading ($heading) {
        $this->heading = $heading;
    }

    /**
    * Get Contents \
    * The content to display in the passage. This field supports HTML formatted data, embedded images, LaTeX and MathML. \
    * @return string $content \
    */
    public function get_content() {
        return $this->content;
    }

    /**
    * Set Contents \
    * The content to display in the passage. This field supports HTML formatted data, embedded images, LaTeX and MathML. \
    * @param string $content \
    */
    public function set_content ($content) {
        $this->content = $content;
    }

    /**
    * Get Contains Mathematics \
    * Set to <strong>true</strong> to have LaTeX or MathML to be rendered with mathjax. \
    * @return boolean $is_math \
    */
    public function get_is_math() {
        return $this->is_math;
    }

    /**
    * Set Contains Mathematics \
    * Set to <strong>true</strong> to have LaTeX or MathML to be rendered with mathjax. \
    * @param boolean $is_math \
    */
    public function set_is_math ($is_math) {
        $this->is_math = $is_math;
    }

    /**
    * Get Math renderer \
    * Choose the rendering engine for math content within a question. Default rendering engine is MathJax with MathQuill for i
	nput areas. If you change this option to "MathQuill", MathQuill will render all math content within a question. \
    * @return string $math_renderer \
    */
    public function get_math_renderer() {
        return $this->math_renderer;
    }

    /**
    * Set Math renderer \
    * Choose the rendering engine for math content within a question. Default rendering engine is MathJax with MathQuill for i
	nput areas. If you change this option to "MathQuill", MathQuill will render all math content within a question. \
    * @param string $math_renderer \
    */
    public function set_math_renderer ($math_renderer) {
        $this->math_renderer = $math_renderer;
    }

    
    public function get_widget_type() {
    return 'feature';
    }
}

