<?php

namespace LearnosityQti\Processors\QtiV2\Marshallers;

use qtism\data\storage\xml\marshalling\Qti21MarshallerFactory;

class LearnosityMarshallerFactory extends Qti21MarshallerFactory
{
    public function __construct()
    {
        parent::__construct();
        $this->addMappingEntry('object', 'LearnosityQti\\Processors\\QtiV2\\Marshallers\\LearnosityObjectMarshaller');
    }
}
