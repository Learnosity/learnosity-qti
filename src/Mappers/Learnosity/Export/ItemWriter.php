<?php

namespace Learnosity\Mappers\Learnosity\Export;

use Learnosity\Entities\Item;

class ItemWriter
{
    public function __construct()
    {
    }

    public function convert(Item $item)
    {
        return $item->to_array();
    }
} 
