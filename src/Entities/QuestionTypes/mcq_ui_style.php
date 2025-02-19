<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class mcq_ui_style extends BaseQuestionTypeAttribute {
    protected $fontsize;
    protected $validation_stem_numeration;
    protected $type;
    protected $choice_label;
    protected $columns;
    protected $orientation;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Font size \
     * Controls the size of base font for this question. Options are among 'small', 'normal', 'large', 'xlarge' and 'xxlarge'. \
     * @return string $fontsize ie. small, normal, large, xlarge, xxlarge  \
     */
    public function get_fontsize() {
        return $this->fontsize;
    }

    /**
     * Set Font size \
     * Controls the size of base font for this question. Options are among 'small', 'normal', 'large', 'xlarge' and 'xxlarge'. \
     * @param string $fontsize ie. small, normal, large, xlarge, xxlarge  \
     */
    public function set_fontsize ($fontsize) {
        $this->fontsize = $fontsize;
    }

    /**
     * Get Stem numeration (review only) \
     * Numeration character to be displayed to the left of the validation label. \
     * @return string $validation_stem_numeration ie. number, upper-alpha, lower-alpha  \
     */
    public function get_validation_stem_numeration() {
        return $this->validation_stem_numeration;
    }

    /**
     * Set Stem numeration (review only) \
     * Numeration character to be displayed to the left of the validation label. \
     * @param string $validation_stem_numeration ie. number, upper-alpha, lower-alpha  \
     */
    public function set_validation_stem_numeration ($validation_stem_numeration) {
        $this->validation_stem_numeration = $validation_stem_numeration;
    }

    /**
     * Get Style \
     * Formatting styles available for the question. \
     * @return string $type ie. horizontal, block, horizontal-input-bottom  \
     */
    public function get_type() {
        return $this->type;
    }

    /**
     * Set Style \
     * Formatting styles available for the question. \
     * @param string $type ie. horizontal, block, horizontal-input-bottom  \
     */
    public function set_type ($type) {
        $this->type = $type;
    }

    /**
     * Get Label type \
     * Numeration character to be displayed to the left of the label content \
     * @return string $choice_label ie. number, upper-alpha, lower-alpha  \
     */
    public function get_choice_label() {
        return $this->choice_label;
    }

    /**
     * Set Label type \
     * Numeration character to be displayed to the left of the label content \
     * @param string $choice_label ie. number, upper-alpha, lower-alpha  \
     */
    public function set_choice_label ($choice_label) {
        $this->choice_label = $choice_label;
    }

    /**
     * Get Number of columns \
     * The amount of columns that the possible responses will be divided across. \
     * @return number $columns \
     */
    public function get_columns() {
        return $this->columns;
    }

    /**
     * Set Number of columns \
     * The amount of columns that the possible responses will be divided across. \
     * @param number $columns \
     */
    public function set_columns ($columns) {
        $this->columns = $columns;
    }

    /**
     * Get Orientation \
     * Sorts the order of the responses vertically or horizontally \
     * @return string $orientation ie. horizontal, vertical  \
     */
    public function get_orientation() {
        return $this->orientation;
    }

    /**
     * Set Orientation \
     * Sorts the order of the responses vertically or horizontally \
     * @param string $orientation ie. horizontal, vertical  \
     */
    public function set_orientation ($orientation) {
        $this->orientation = $orientation;
    }

    
}

