<?php

namespace LearnosityQti\Processors\QtiV2\In;

use LearnosityQti\Entities\Activity\activity;
use LearnosityQti\Entities\Activity\activity_data;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Services\LogService;
use qtism\data\AssessmentItem;
use qtism\data\AssessmentTest;
use qtism\data\storage\xml\XmlDocument;

class TestMapper
{
    public function parse($xmlString, $validate = true)
    {
        // TODO: Remove this, and move it higher up
        LogService::flush();

        $xmlDocument = new XmlDocument();
        if ($validate === false) {
            LogService::log('QTI pre-validation is turned off, some invalid attributes might be stripped from XML content upon conversion');
        }
        $xmlDocument->loadFromString($xmlString, $validate);

        /** @var AssessmentItem $assessmentItem */
        $assessmentTest = $xmlDocument->getDocumentComponent();
        if (!($assessmentTest instanceof AssessmentTest)) {
            throw new MappingException('XML is not a valid <assessmentItem> document');
        }

        // Ignore `testPart` and `assessmentSection`. Grab every item references and merge in array
        $itemReferences = [];
        foreach ($assessmentTest->getComponentsByClassName('assessmentItemRef', true) as $assessmentItemRef) {
            $itemReferences[] = $assessmentItemRef->getIdentifier();
        }
        LogService::log('Support for mapping is very limited. Elements such `testPart`, `assessmentSections`, `seclection`, `rubricBlock`, '
            . 'etc are ignored. Please see developer docs for more details');

        $data = new activity_data();
        $data->set_items($itemReferences);
        $activity = new activity($assessmentTest->getIdentifier(), $data);

        // Flush out all the error messages stored in this static class, also ensure they are unique
        $messages = array_values(array_unique(LogService::flush()));
        return [$activity, $messages];
    }
}
