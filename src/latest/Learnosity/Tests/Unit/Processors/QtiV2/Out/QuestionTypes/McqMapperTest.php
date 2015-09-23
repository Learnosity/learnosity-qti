<?php

namespace Learnosity\Tests\Unit\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\QuestionTypes\mcq;
use Learnosity\Entities\QuestionTypes\mcq_options_item;
use Learnosity\Entities\QuestionTypes\mcq_validation;
use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use Learnosity\Processors\QtiV2\Out\Constants;
use Learnosity\Processors\QtiV2\Out\QuestionTypes\McqMapper;
use Learnosity\Utils\QtiMarshallerUtil;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\content\interactions\ChoiceInteraction;
use qtism\data\content\interactions\Orientation;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\processing\ResponseProcessing;
use qtism\data\state\ResponseDeclaration;

class McqMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleCaseWithNoValidation()
    {
        $stimulus = '<strong>Where is Learnosity office in Australia located?</strong>';
        $options  = [
            'ChoiceA' => 'Melbourne',
            'ChoiceB' => 'Sydney',
            'ChoiceC' => 'Jakarta',
        ];
        $question = $this->buildMcq($options);
        $question->set_stimulus($stimulus);

        $mcqMapper = new McqMapper();
        /** @var ChoiceInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) =
            $mcqMapper->convert($question, 'testIdentifier', 'testIdentifierLabel');

        // Check usual
        $this->assertTrue($interaction instanceof ChoiceInteraction);
        $this->assertEquals('testIdentifier', $interaction->getResponseIdentifier());
        $this->assertEquals('testIdentifierLabel', $interaction->getLabel());
        $this->assertEquals($stimulus, QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents()));
        $this->assertNull($responseDeclaration);
        $this->assertNull($responseProcessing);

        // Check `options` mapping are correct
        /** @var SimpleChoice[] $choices */
        $choices = $interaction->getSimpleChoices()->getArrayCopy(true);
        $this->assertEquals('ChoiceA', $choices[0]->getIdentifier());
        $this->assertEquals('Melbourne', QtiMarshallerUtil::marshallCollection($choices[0]->getContent()));
        $this->assertEquals('ChoiceB', $choices[1]->getIdentifier());
        $this->assertEquals('Sydney', QtiMarshallerUtil::marshallCollection($choices[1]->getContent()));
        $this->assertEquals('ChoiceC', $choices[2]->getIdentifier());
        $this->assertEquals('Jakarta', QtiMarshallerUtil::marshallCollection($choices[2]->getContent()));

        // Check the default values are correct
        $this->assertEquals(1, $interaction->getMaxChoices());
        $this->assertEquals(1, $interaction->getMinChoices());
        $this->assertEquals(Orientation::VERTICAL, $interaction->getOrientation());
    }

    public function testMcqWithExactMatchValidation()
    {
        $question = $this->buildMcq([
            'ChoiceA' => 'Melbourne',
            'ChoiceB' => 'Sydney',
            'ChoiceC' => 'Jakarta',
        ]);
        $question->set_stimulus('<strong>Where is Learnosity office in Australia located?</strong>');

        /** @var mcq_validation $validation */
        $validation = ValidationBuilder::build('mcq', 'exactMatch', [
            new ValidResponse(1, ['ChoiceB'])
        ]);
        $question->set_validation($validation);

        $mcqMapper = new McqMapper();
        /** @var ResponseDeclaration $responseDeclaration */
        /** @var ResponseProcessing $responseProcessing */
        list($interaction, $responseDeclaration, $responseProcessing) =
            $mcqMapper->convert($question, 'testIdentifier', 'testIdentifierLabel');
        $this->assertTrue($interaction instanceof ChoiceInteraction);

        // Check on the responseDeclaration and responseProcessing objects to be correctly generated
        $this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT, $responseProcessing->getTemplate());
        $this->assertEquals(Cardinality::SINGLE, $responseDeclaration->getCardinality());
        $this->assertEquals(BaseType::IDENTIFIER, $responseDeclaration->getBaseType());
        $this->assertNotNull($responseDeclaration->getCorrectResponse());
        $this->assertEquals('ChoiceB', $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true)[0]->getValue());

        $this->assertNull($responseDeclaration->getMapping());
    }

    /**
     * Convenient builder function to quickly build `mcq` object given key value pair as options
     * The array key would be mapped as option `value` and its value would be mapped as `label`
     * @param array $options ie. ['choicea' => 'Choice A', 'choiceb' => 'Choice B']
     * @return mcq
     */
    private function buildMcq(array $options)
    {
        $mcqOptionsItems = [];
        foreach ($options as $key => $label) {
            $option = new mcq_options_item();
            $option->set_value($key);
            $option->set_label($label);
            $mcqOptionsItems[] = $option;
        }
        return new mcq('longtext', $mcqOptionsItems);
    }
}
