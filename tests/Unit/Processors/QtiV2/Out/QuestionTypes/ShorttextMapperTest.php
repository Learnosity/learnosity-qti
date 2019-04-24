<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\QuestionTypes\shorttext;
use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use LearnosityQti\Processors\QtiV2\Out\Constants;
use LearnosityQti\Processors\QtiV2\Out\QuestionTypes\ShorttextMapper;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\content\xhtml\text\Div;
use qtism\data\processing\ResponseProcessing;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class ShorttextMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleCaseWithSimpleValidation()
    {
        $question = $this->buildShorttextWithValidation([
            new ValidResponse(1, ['testhello'])
        ]);
        $mapper = new ShorttextMapper();
        /** @var TextEntryInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert(
            $question,
            'reference',
            'reference'
        );

        // Not going to test the interaction, too boring
        $this->assertTrue($interaction instanceof Div);
        $this->assertTrue($interaction->getComponents()->getArrayCopy()[0] instanceof TextEntryInteraction);

        // Shorttext shall have one simple exactMatch <responseDeclaration> and <responseProcessing>
        /** @var ResponseProcessing $responseProcessing */
        $this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MAP_RESPONSE, $responseProcessing->getTemplate());

        /** @var ResponseDeclaration $responseDeclaration */
        /** @var Value[] $values */
        $values = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
        $this->assertEquals('testhello', $values[0]->getValue());

        /** @var MapEntry[] $mapEntries */
        $mapEntries = $responseDeclaration->getMapping()->getMapEntries()->getArrayCopy(true);
        $this->assertEquals('testhello', $mapEntries[0]->getMapKey());
        $this->assertEquals(1, $mapEntries[0]->getMappedValue());
    }

    public function testSimpleCaseWithMultipleValidation()
    {
        $question = $this->buildShorttextWithValidation([
            new ValidResponse(1, ['testhello']),
            new ValidResponse(2, ['testhello2']),
            new ValidResponse(5, ['testhello3'])
        ]);
        $mapper = new ShorttextMapper();
        /** @var TextEntryInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert($question, 'reference', 'reference');

        // Not going to test the interaction, too boring
        $this->assertTrue($interaction instanceof Div);
        $this->assertTrue($interaction->getComponents()->getArrayCopy()[0] instanceof TextEntryInteraction);

        // Shorttext shall have one simple exactMatch <responseDeclaration> and <responseProcessing>
        /** @var ResponseProcessing $responseProcessing */
        $this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MAP_RESPONSE, $responseProcessing->getTemplate());

        /** @var ResponseDeclaration $responseDeclaration */
        /** @var MapEntry[] $mapEntries */
        $mapEntries = $responseDeclaration->getMapping()->getMapEntries()->getArrayCopy(true);
        $this->assertEquals('testhello3', $mapEntries[0]->getMapKey());
        $this->assertEquals(5, $mapEntries[0]->getMappedValue());
        $this->assertEquals(false, $mapEntries[0]->isCaseSensitive());
        $this->assertEquals('testhello2', $mapEntries[1]->getMapKey());
        $this->assertEquals(2, $mapEntries[1]->getMappedValue());
        $this->assertEquals(false, $mapEntries[1]->isCaseSensitive());
        $this->assertEquals('testhello', $mapEntries[2]->getMapKey());
        $this->assertEquals(1, $mapEntries[2]->getMappedValue());
        $this->assertEquals(false, $mapEntries[2]->isCaseSensitive());
    }

    public function testShouldHandleInCaseSensitivity()
    {
        $question = $this->buildShorttextWithValidation([
            new ValidResponse(1, ['testhello']),
            new ValidResponse(2, ['testhello2']),
            new ValidResponse(5, ['testhello3'])
        ]);
        $question->set_case_sensitive(false);
        $mapper = new ShorttextMapper();
        /** @var TextEntryInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert($question, 'reference', 'reference');

        // Shorttext shall have one simple exactMatch <responseDeclaration> and <responseProcessing>
        /** @var ResponseProcessing $responseProcessing */
        $this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MAP_RESPONSE, $responseProcessing->getTemplate());

        /** @var ResponseDeclaration $responseDeclaration */
        /** @var MapEntry[] $mapEntries */
        $mapEntries = $responseDeclaration->getMapping()->getMapEntries()->getArrayCopy(true);
        $this->assertEquals('testhello3', $mapEntries[0]->getMapKey());
        $this->assertEquals(5, $mapEntries[0]->getMappedValue());
        $this->assertEquals(false, $mapEntries[0]->isCaseSensitive());
        $this->assertEquals('testhello2', $mapEntries[1]->getMapKey());
        $this->assertEquals(2, $mapEntries[1]->getMappedValue());
        $this->assertEquals(false, $mapEntries[1]->isCaseSensitive());
        $this->assertEquals('testhello', $mapEntries[2]->getMapKey());
        $this->assertEquals(1, $mapEntries[2]->getMappedValue());
        $this->assertEquals(false, $mapEntries[2]->isCaseSensitive());
    }

    private function buildShorttextWithValidation(array $validResponses)
    {
        $question = new shorttext('shorttext');
        $question->set_placeholder('placeholdertest');
        $question->set_stimulus('<strong>stimulushere</strong>');

        $validation = ValidationBuilder::build('shorttext', 'exactMatch', $validResponses);
        $question->set_validation($validation);

        return $question;
    }
}
