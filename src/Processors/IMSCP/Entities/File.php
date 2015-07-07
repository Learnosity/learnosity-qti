<?php

namespace Learnosity\Processors\IMSCP\Entities;

class File
{
    protected $href;

    /**
     * @return mixed
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @param mixed $href
     */
    public function setHref($href)
    {
        $this->href = $href;
    }
}
