<?php

namespace Learnosity\Processors\Learnosity\Out;

use Learnosity\Entities\Item\item;

class ItemWriter
{
    public function convert(item $item)
    {
        return $item->to_array();
    }
}
