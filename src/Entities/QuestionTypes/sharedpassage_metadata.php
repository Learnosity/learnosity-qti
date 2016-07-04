<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.86.0","feedback":"v2.71.0","features":"v2.84.0"}
 */
class sharedpassage_metadata extends BaseQuestionTypeAttribute
{
    protected $lexile;
    protected $flesch_kincaid;

    public function __construct()
    {
    }

    /**
     * Get Lexile \
     * The Lexile framework for reading is an educational tool that uses a measure called a Lexile to match readers with books,
     * articles and other leveled reading resources. \
     *
     * @return string $lexile \
     */
    public function get_lexile()
    {
        return $this->lexile;
    }

    /**
     * Set Lexile \
     * The Lexile framework for reading is an educational tool that uses a measure called a Lexile to match readers with books,
     * articles and other leveled reading resources. \
     *
     * @param string $lexile \
     */
    public function set_lexile($lexile)
    {
        $this->lexile = $lexile;
    }

    /**
     * Get Flesch-Kincaid \
     * A grade level, making it easier for teachers, parents, librarians, and others to judge the readability level of various
     * books and texts. \
     *
     * @return string $flesch_kincaid \
     */
    public function get_flesch_kincaid()
    {
        return $this->flesch_kincaid;
    }

    /**
     * Set Flesch-Kincaid \
     * A grade level, making it easier for teachers, parents, librarians, and others to judge the readability level of various
     * books and texts. \
     *
     * @param string $flesch_kincaid \
     */
    public function set_flesch_kincaid($flesch_kincaid)
    {
        $this->flesch_kincaid = $flesch_kincaid;
    }


}

