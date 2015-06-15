<?php

namespace Learnosity\Mappers\Learnosity\Import;

class ItemMapper
{
    public function parse(array $itemJson)
    {
        $item = EntityBuilder::build('Learnosity\Entities\Item', $itemJson);
        return $item;
    }
}
