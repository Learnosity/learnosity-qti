<?php

namespace LearnosityQti\Processors\Learnosity\In;

use LearnosityQti\Entities\Item\item;

class ItemMapper
{
    /**
     * @return item
     * @throws \Learnosity\Exceptions\MappingException
     */
    public function parse(array $itemJson)
    {
        $item = EntityBuilder::build('LearnosityQti\Entities\Item\item', $itemJson);
        return $item;
    }
}
