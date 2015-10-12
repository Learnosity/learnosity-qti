<?php

namespace LearnosityQti\Processors\Learnosity\Out;

use LearnosityQti\Entities\Item\item;

class ItemWriter
{
    public function convert(item $item)
    {
        return $item->to_array();
    }
}
