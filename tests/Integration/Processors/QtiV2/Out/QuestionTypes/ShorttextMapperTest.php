<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Processors\QtiV2\Out\Constants;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class ShorttextMapperTest extends AbstractQuestionTypeTest
{
    public function testShorttextQuestionWithSimpleValidation()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/shorttext.json'), true);
        $assessmentItem = $this->convertToAssessmentItem($data);

        // Has <textEntryInteraction> as the first and only interaction
        /** @var TextEntryInteraction $interaction */
        $interaction = $assessmentItem->getComponentsByClassName('textEntryInteraction', true)->getArrayCopy()[0];

        // Test basic attributes
        $this->assertTrue($interaction instanceof TextEntryInteraction);
        $this->assertEquals('placeholdertext', $interaction->getPlaceholderText());
        $this->assertEquals(15, $interaction->getExpectedLength());

        // Shorttext shall have one simple `map_response` <responseDeclaration> and <responseProcessing>
        /** @var ResponseDeclaration $responseDeclaration */
        $responseDeclaration = $assessmentItem->getResponseDeclarations()->getArrayCopy()[0];
        $this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MAP_RESPONSE, $assessmentItem->getResponseProcessing()->getTemplate());

        /** @var Value[] $values */
        $values = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
        $this->assertEquals('hello', $values[0]->getValue());
        $this->assertEquals('anotherhello', $values[1]->getValue());

        /** @var MapEntry[] $mapEntries */
        $mapEntries = $responseDeclaration->getMapping()->getMapEntries()->getArrayCopy(true);
        $this->assertEquals('hello', $mapEntries[0]->getMapKey());
        $this->assertEquals(2, $mapEntries[0]->getMappedValue());
        $this->assertEquals('anotherhello', $mapEntries[1]->getMapKey());
        $this->assertEquals(1, $mapEntries[1]->getMappedValue());

        // Check itembody is correct that the stimulus is appended before
        $itemBodyContent = QtiMarshallerUtil::marshallCollection($assessmentItem->getItemBody()->getComponents());
        $expectedString = '<p>[This is the stem.]</p><div><textEntryInteraction responseIdentifier="shorttexttestreference" expectedLength="15" placeholderText="placeholdertext" label="shorttexttestreference"/></div>';
        $this->assertEquals($expectedString, $itemBodyContent);
    }
}
