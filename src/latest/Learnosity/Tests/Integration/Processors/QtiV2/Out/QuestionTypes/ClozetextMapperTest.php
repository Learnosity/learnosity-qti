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

        // Assert response processing template
        $this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT, $assessmentItem->getResponseProcessing()->getTemplate());

        // Assert response declarations
        $responseDeclarations = $assessmentItem->getResponseDeclarations()->getArrayCopy();
        /** @var ResponseDeclaration $responseDeclarationOne */
        $responseDeclarationOne = $responseDeclarations[0];
        /** @var ResponseDeclaration $responseDeclarationTwo */
        $responseDeclarationTwo = $responseDeclarations[1];

        // Check has the correct identifiers, also correct `correctResponse` values
        $this->assertEquals($responseDeclarationOne->getIdentifier(), $interactionOne->getResponseIdentifier());
        $this->assertNull($responseDeclarationOne->getMapping());
        $this->assertEquals('responseone', $responseDeclarationOne->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());

        $this->assertEquals($responseDeclarationTwo->getIdentifier(), $interactionTwo->getResponseIdentifier());
        $this->assertNull($responseDeclarationTwo->getMapping());
        $this->assertEquals('responsetwo', $responseDeclarationTwo->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
    }
}
