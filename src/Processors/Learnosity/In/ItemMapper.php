<?php

namespace Learnosity\Processors\Learnosity\In;

class ItemMapper
{
    public function parse(array $itemJson)
    {
        $item = EntityBuilder::build('Learnosity\Entities\Item\item', $itemJson);
        return $item;
    }
}
