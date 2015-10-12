<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class imagetool extends BaseQuestionType {
    protected $type;
    protected $metadata;
    protected $image;
    protected $rotate;
    protected $width;
    protected $height;
    protected $button;
    protected $buttonicon;
    protected $label;
    
    public function __construct(
                    $type,
                                $image
                        )
    {
                $this->type = $type;
                $this->image = $image;
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
    * Get Image \
    * Supports a protractor, ruler-15-cm, ruler-30-cm, ruler-6-inches, ruler-12-inches or a URI to an image. \
    * @return string $image \
    */
    public function get_image() {
        return $this->image;
    }

    /**
    * Set Image \
    * Supports a protractor, ruler-15-cm, ruler-30-cm, ruler-6-inches, ruler-12-inches or a URI to an image. \
    * @param string $image \
    */
    public function set_image ($image) {
        $this->image = $image;
    }

    /**
    * Get Show rotate icon? \
    * Renders a rotate icon that is draggable and allows the image rotate. \
    * @return boolean $rotate \
    */
    public function get_rotate() {
        return $this->rotate;
    }

    /**
    * Set Show rotate icon? \
    * Renders a rotate icon that is draggable and allows the image rotate. \
    * @param boolean $rotate \
    */
    public function set_rotate ($rotate) {
        $this->rotate = $rotate;
    }

    /**
    * Get Width \
    * Width of the image in pixels. Can only be used with custom URI image. \
    * @return string $width \
    */
    public function get_width() {
        return $this->width;
    }

    /**
    * Set Width \
    * Width of the image in pixels. Can only be used with custom URI image. \
    * @param string $width \
    */
    public function set_width ($width) {
        $this->width = $width;
    }

    /**
    * Get Height \
    * Height of the image in pixels. Can only be used with custom URI image. \
    * @return string $height \
    */
    public function get_height() {
        return $this->height;
    }

    /**
    * Set Height \
    * Height of the image in pixels. Can only be used with custom URI image. \
    * @param string $height \
    */
    public function set_height ($height) {
        $this->height = $height;
    }

    /**
    * Get Button \
    * Renders a button for toggling the image tool. \
    * @return boolean $button \
    */
    public function get_button() {
        return $this->button;
    }

    /**
    * Set Button \
    * Renders a button for toggling the image tool. \
    * @param boolean $button \
    */
    public function set_button ($button) {
        $this->button = $button;
    }

    /**
    * Get Button Icon \
    * Supports a protractor, ruler, generic or a URI to an image. \
    * @return string $buttonicon \
    */
    public function get_buttonicon() {
        return $this->buttonicon;
    }

    /**
    * Set Button Icon \
    * Supports a protractor, ruler, generic or a URI to an image. \
    * @param string $buttonicon \
    */
    public function set_buttonicon ($buttonicon) {
        $this->buttonicon = $buttonicon;
    }

    /**
    * Get Label \
    * Toggle button label text to display. \
    * @return string $label \
    */
    public function get_label() {
        return $this->label;
    }

    /**
    * Set Label \
    * Toggle button label text to display. \
    * @param string $label \
    */
    public function set_label ($label) {
        $this->label = $label;
    }

    
    public function get_widget_type() {
    return 'feature';
    }
}

