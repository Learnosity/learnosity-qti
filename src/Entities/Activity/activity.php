<?php

namespace LearnosityQti\Entities\Activity;

use LearnosityQti\Entities\BaseEntity;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.86.0","feedback":"v2.71.0","features":"v2.84.0"}
 */
class activity extends BaseEntity
{
    protected $reference;
    protected $description;
    protected $data;

    public function __construct(
        $reference,
        activity_data $data
    ) {
        $this->reference = $reference;
        $this->data      = $data;
    }

    /**
     * Get reference \
     *  \
     *
     * @return string $reference \
     */
    public function get_reference()
    {
        return $this->reference;
    }

    /**
     * Set reference \
     *  \
     *
     * @param string $reference \
     */
    public function set_reference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * Get description \
     *  \
     *
     * @return string $description \
     */
    public function get_description()
    {
        return $this->description;
    }

    /**
     * Set description \
     *  \
     *
     * @param string $description \
     */
    public function set_description($description)
    {
        $this->description = $description;
    }

    /**
     * Get data \
     *  \
     *
     * @return activity_data $data \
     */
    public function get_data()
    {
        return $this->data;
    }

    /**
     * Set data \
     *  \
     *
     * @param activity_data $data \
     */
    public function set_data(activity_data $data)
    {
        $this->data = $data;
    }


}

