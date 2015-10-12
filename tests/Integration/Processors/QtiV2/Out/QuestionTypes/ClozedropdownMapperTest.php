<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Processors\QtiV2\Out\Constants;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\AssessmentItem;
use qtism\data\content\interactions\InlineChoiceInteraction;
use qtism\data\state\ResponseDeclaration;

class ClozedropdownMapperTest extends AbstractQuestionTypeTest
{
    public function testSimpleCase()
    {
        /** @var AssessmentItem $assessmentItem */
        $question = json_decode($this->getFixtureFileContents('learnosityjsons/data_clozedropdown.json'), true);
        $assessmentItem = $this->convertToAssessmentItem($question);

        $interactions = $assessmentItem->getComponentsByClassName('inlineChoiceInteraction', true)->getArrayCopy();
        /** @var InlineChoiceInteraction $interactionOne */
        $interactionOne = $interactions[0];
        /** @var InlineChoiceInteraction $interactionTwo */
        $interactionTwo = $interactions[1];
        $this->assertTrue($interactionOne instanceof InlineChoiceInteraction);
        $this->assertTrue($interactionTwo instanceof InlineChoiceInteraction);

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
        $this->assertEquals('INLINECHOICE_2', $responseDeclarationOne->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
        $this->assertEquals('Choice C', QtiMarshallerUtil::marshallCollection($interactionOne->getComponentByIdentifier('INLINECHOICE_2')->getComponents()));

        $this->assertEquals($responseDeclarationTwo->getIdentifier(), $interactionTwo->getResponseIdentifier());
        $this->assertNull($responseDeclarationTwo->getMapping());
        $this->assertEquals('INLINECHOICE_1', $responseDeclarationTwo->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
        $this->assertEquals('Choice B', QtiMarshallerUtil::marshallCollection($interactionTwo->getComponentByIdentifier('INLINECHOICE_1')->getComponents()));
    }
}
