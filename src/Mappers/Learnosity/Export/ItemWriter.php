<?php

namespace Learnosity\Mappers\Learnosity\Export;

use Learnosity\Entities\Item\item;

class ItemWriter
{
    public function __construct()
    {
    }

    public function convert(item $item)
    {
        return $item->to_array();
    }
} 
