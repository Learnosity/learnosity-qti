<?php

namespace Learnosity\Mappers\IMSCP\Entities;


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