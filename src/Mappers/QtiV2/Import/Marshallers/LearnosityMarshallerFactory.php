<?php

namespace Learnosity\Mappers\QtiV2\Import\Marshallers;

use qtism\data\storage\xml\marshalling\MarshallerFactory;

class LearnosityMarshallerFactory extends MarshallerFactory
{
    public function __construct()
    {
        parent::__construct();
        $this->addMappingEntry('object', 'Learnosity\\Mappers\\QtiV2\\Import\\Marshallers\\LearnosityObjectMarshaller');
    }
}
