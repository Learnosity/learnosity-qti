<?php

namespace LearnosityQti\Processors\QtiV2\In;

use LearnosityQti\AppContainer;
use LearnosityQti\Entities\Activity\activity;
use LearnosityQti\Entities\Activity\activity_data;
use LearnosityQti\Entities\Activity\activity_data_sections_item;
use LearnosityQti\Entities\Activity\activity_data_sections_item_config;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\In\Processings\ProcessingInterface;
use LearnosityQti\Services\LogService;
use qtism\data\AssessmentItem;
use qtism\data\AssessmentItemRef;
use qtism\data\AssessmentSection;
use qtism\data\AssessmentSectionCollection;
use qtism\data\AssessmentTest;
use qtism\data\content\ItemBody;
use qtism\data\processing\ResponseProcessing;
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

        // TODO: Write up the exceptions!
        return [$this->buildActivity($assessmentTest), []];
    }

    private function buildActivity(AssessmentTest $assessmentTest)
    {
        $data = new activity_data();

        // Support on sections first
        $sectionCollection = $assessmentTest->getComponentsByClassName('assessmentSection', true);
        $sections = [];
        /** @var AssessmentSection $assessmentSection */
        foreach ($sectionCollection as $assessmentSection) {
            $references = [];

            // Populate item references
            $itemRefCollection = $assessmentSection->getComponentsByClassName('assessmentItemRef', true);
            /** @var AssessmentItemRef $assessmentItemRef */
            foreach ($itemRefCollection as $assessmentItemRef) {
                $references[] = $assessmentItemRef->getIdentifier();
            }
            $section = new activity_data_sections_item();
            $section->set_items($references);

            // Set assessment section title if exists
            if (!empty($assessmentSection->getTitle())) {
                $sectionConfig = new activity_data_sections_item_config();
                $sectionConfig->set_subtitle($assessmentSection->getTitle());
                $section->set_config($sectionConfig);
            }

            $sections[] = $section;
        }
        $data->set_sections($sections);
        $reference = $assessmentTest->getIdentifier();
        $activity = new activity($reference, $data);

        // Return dah` activity~!
        return $activity;
    }
}
