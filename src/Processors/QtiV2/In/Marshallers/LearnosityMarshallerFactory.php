<?php

namespace Learnosity\Processors\QtiV2\In\Marshallers;

use qtism\data\storage\xml\marshalling\MarshallerFactory;

class LearnosityMarshallerFactory extends MarshallerFactory
{
    public function __construct()
    {
        parent::__construct();
        $this->addMappingEntry('object', 'Learnosity\\Processors\\QtiV2\\In\\Marshallers\\LearnosityObjectMarshaller');
    }
}
