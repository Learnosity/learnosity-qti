<?php

namespace Learnosity\Processors\Learnosity\In;

use Learnosity\Entities\Item\item;

class ItemMapper
{
    /**
     * @return item
     * @throws \Learnosity\Exceptions\MappingException
     */
    public function parse(array $itemJson)
    {
        $item = EntityBuilder::build('Learnosity\Entities\Item\item', $itemJson);
        return $item;
    }
}
