<?php

namespace Learnosity\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Processors\QtiV2\Out\Constants;
use Learnosity\Utils\QtiMarshallerUtil;
use qtism\data\AssessmentItem;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\state\ResponseDeclaration;

class ClozetextMapperTest extends AbstractQuestionTypeTest
{
    public function testSimpleCase()
    {
        /** @var AssessmentItem $assessmentItem */
        $question = json_decode($this->getFixtureFileContents('learnosityjsons/data_clozetext.json'), true);
        $assessmentItem = $this->convertToAssessmentItem($question);

        $interactions = $assessmentItem->getComponentsByClassName('textEntryInteraction', true)->getArrayCopy();
        /** @var TextEntryInteraction $interactionOne */
        $interactionOne = $interactions[0];
        /** @var TextEntryInteraction $interactionTwo */
        $interactionTwo = $interactions[1];
        $this->assertTrue($interactionOne instanceof TextEntryInteraction);
        $this->assertTrue($interactionTwo instanceof TextEntryInteraction);
        $this->assertEquals(15, $interactionOne->getExpectedLength());
        $this->assertEquals(15, $interactionTwo->getExpectedLength());

        $content = QtiMarshallerUtil::marshallCollection($assessmentItem->getItemBody()->getContent());
        $this->assertNotEmpty($content);

        // Assert response declarations
        $responseDeclarations = $assessmentItem->getResponseDeclarations()->getArrayCopy();
        /** @var ResponseDeclaration $responseDeclarationOne */
        $responseDeclarationOne = $responseDeclarations[0];
        /** @var ResponseDeclaration $responseDeclarationTwo */
        $responseDeclarationTwo = $responseDeclarations[1];

        // Check has the correct identifiers
        $this->assertEquals($responseDeclarationOne->getIdentifier(), $interactionOne->getResponseIdentifier());
        $this->assertEquals($responseDeclarationTwo->getIdentifier(), $interactionTwo->getResponseIdentifier());

        // Also correct `correctResponse` values
        $this->assertEquals('responseone', $responseDeclarationOne->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
        $this->assertEquals('otherresponseone', $responseDeclarationOne->getCorrectResponse()->getValues()->getArrayCopy()[1]->getValue());
        $this->assertEquals('anotherresponseone', $responseDeclarationOne->getCorrectResponse()->getValues()->getArrayCopy()[2]->getValue());
        $this->assertEquals('responsetwo', $responseDeclarationTwo->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
        $this->assertEquals('otherresponsetwo', $responseDeclarationTwo->getCorrectResponse()->getValues()->getArrayCopy()[1]->getValue());
        $this->assertEquals('anotherresponsetwo', $responseDeclarationTwo->getCorrectResponse()->getValues()->getArrayCopy()[2]->getValue());

        // Also correct `mapping` entries
        $this->assertEquals('responseone', $responseDeclarationOne->getMapping()->getMapEntries()->getArrayCopy()[0]->getMapKey());
        $this->assertEquals(3, $responseDeclarationOne->getMapping()->getMapEntries()->getArrayCopy()[0]->getMappedValue());
        $this->assertEquals('otherresponseone', $responseDeclarationOne->getMapping()->getMapEntries()->getArrayCopy()[1]->getMapKey());
        $this->assertEquals(2, $responseDeclarationOne->getMapping()->getMapEntries()->getArrayCopy()[1]->getMappedValue());
        $this->assertEquals('anotherresponseone', $responseDeclarationOne->getMapping()->getMapEntries()->getArrayCopy()[2]->getMapKey());
        $this->assertEquals(1, $responseDeclarationOne->getMapping()->getMapEntries()->getArrayCopy()[2]->getMappedValue());

        $this->assertEquals('responsetwo', $responseDeclarationTwo->getMapping()->getMapEntries()->getArrayCopy()[0]->getMapKey());
        $this->assertEquals(3, $responseDeclarationTwo->getMapping()->getMapEntries()->getArrayCopy()[0]->getMappedValue());
        $this->assertEquals('otherresponsetwo', $responseDeclarationTwo->getMapping()->getMapEntries()->getArrayCopy()[1]->getMapKey());
        $this->assertEquals(2, $responseDeclarationTwo->getMapping()->getMapEntries()->getArrayCopy()[1]->getMappedValue());
        $this->assertEquals('anotherresponsetwo', $responseDeclarationTwo->getMapping()->getMapEntries()->getArrayCopy()[2]->getMapKey());
        $this->assertEquals(1, $responseDeclarationTwo->getMapping()->getMapEntries()->getArrayCopy()[2]->getMappedValue());

        // Assert response processing template
        $this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MAP_RESPONSE, $assessmentItem->getResponseProcessing()->getTemplate());
    }
}
