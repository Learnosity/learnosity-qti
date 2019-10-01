<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Processors\QtiV2\Out\Constants;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\common\datatypes\QtiDirectedPair;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\content\interactions\MatchInteraction;
use qtism\data\content\interactions\SimpleAssociableChoice;
use qtism\data\content\ModalFeedbackCollection;
use qtism\data\content\ModalFeedback;
use qtism\data\rules\ResponseElse;
use qtism\data\rules\ResponseIf;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;
use ReflectionProperty;

class ChoicematrixMapperTest extends AbstractQuestionTypeTest
{
    public function testSimpleCase()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/choicematrix.json'), true);
        $assessmentItemArray = $this->convertToAssessmentItem($data);
       
		foreach ($assessmentItemArray as $assessmentItem) {
			/** @var MatchInteraction $interaction */
			$interaction = $assessmentItem->getComponentsByClassName('matchInteraction', true)->getArrayCopy()[0];
			$this->assertTrue($interaction instanceof MatchInteraction);

			// And its prompt is mapped correctly
			$promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
			$this->assertEquals('<p>[This is the stem.]</p>', trim($promptString));

			// Assert its source choices (stems)    
			/** @var SimpleAssociableChoice[] $stemAssociableChoices */
			$stemAssociableChoices = $interaction->getSourceChoices()->getSimpleAssociableChoices()->getArrayCopy(true);
			$this->assertEquals('[Stem 1]', QtiMarshallerUtil::marshallCollection($stemAssociableChoices[0]->getContent()));
			$this->assertEquals('STEM_0', $stemAssociableChoices[0]->getIdentifier());
			$this->assertEquals('[Stem 2]', QtiMarshallerUtil::marshallCollection($stemAssociableChoices[1]->getContent()));
			$this->assertEquals('STEM_1', $stemAssociableChoices[1]->getIdentifier());
			$this->assertEquals('[Stem 3]', QtiMarshallerUtil::marshallCollection($stemAssociableChoices[2]->getContent()));
			$this->assertEquals('STEM_2', $stemAssociableChoices[2]->getIdentifier());
			$this->assertEquals('[Stem 4]', QtiMarshallerUtil::marshallCollection($stemAssociableChoices[3]->getContent()));
			$this->assertEquals('STEM_3', $stemAssociableChoices[3]->getIdentifier());
			foreach ($stemAssociableChoices as $choice) {
				$this->assertEquals(1, $choice->getMatchMax());
				$this->assertEquals(1, $choice->getMatchMin());
			}

			// Assert its target choices (options)
			/** @var SimpleAssociableChoice[] $optionAssociableChoices */
			$optionAssociableChoices = $interaction->getTargetChoices()->getSimpleAssociableChoices()->getArrayCopy(true);
			$this->assertEquals('True', QtiMarshallerUtil::marshallCollection($optionAssociableChoices[0]->getContent()));
			$this->assertEquals('OPTION_0', $optionAssociableChoices[0]->getIdentifier());
			$this->assertEquals('False', QtiMarshallerUtil::marshallCollection($optionAssociableChoices[1]->getContent()));
			$this->assertEquals('OPTION_1', $optionAssociableChoices[1]->getIdentifier());
			foreach ($optionAssociableChoices as $choice) {
				$this->assertEquals(4, $choice->getMatchMax());
				$this->assertEquals(1, $choice->getMatchMin());
			}

			// Assert its valdation, woo hooo!
			$this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT, $assessmentItem->getResponseProcessing()->getTemplate());
			/** @var ResponseDeclaration $responseDeclaration */
			$responseDeclaration = $assessmentItem->getResponseDeclarations()->getArrayCopy()[0];
			$this->assertEquals(Cardinality::MULTIPLE, $responseDeclaration->getCardinality());
			$this->assertEquals(BaseType::DIRECTED_PAIR, $responseDeclaration->getBaseType());

			/** @var Value[] $values */
			$values = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
			$this->assertDirectPair($values[0]->getValue(), 'STEM_0', 'OPTION_0');
			$this->assertDirectPair($values[1]->getValue(), 'STEM_1', 'OPTION_1');
			$this->assertDirectPair($values[2]->getValue(), 'STEM_2', 'OPTION_0');
			$this->assertDirectPair($values[3]->getValue(), 'STEM_3', 'OPTION_1');

			$this->assertNull($responseDeclaration->getMapping());
		}
    }
	
	public function testWithDistractorRationale()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/choice_matrix_metadata.json'), true);
        $mock = $this->getMock('ConvertToQtiService', array('getFormat'));
        
	    // Replace protected self reference with mock object
        $ref = new ReflectionProperty('LearnosityQti\Services\ConvertToQtiService', 'instance');
	    $ref->setAccessible(true);
	    $ref->setValue(null, $mock);
            
        /*$format = $mock->expects($this->once())
				->method('getFormat')
				->will($this->returnValue('qti'));*/
		
		$assessmentItemArray = $this->convertToAssessmentItem($data);

		foreach ($assessmentItemArray as $assessmentItem) {
			/** @var MatchInteraction $interaction */
			$interaction = $assessmentItem->getComponentsByClassName('matchInteraction', true)->getArrayCopy()[0];
			$this->assertTrue($interaction instanceof MatchInteraction);

			// And its prompt is mapped correctly
			$promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
			$this->assertEquals('<p>[This is the stem.]</p>', trim($promptString));

			// Assert its source choices (stems)
			/** @var SimpleAssociableChoice[] $stemAssociableChoices */
			$stemAssociableChoices = $interaction->getSourceChoices()->getSimpleAssociableChoices()->getArrayCopy(true);
			$this->assertEquals('[Stem 1]', QtiMarshallerUtil::marshallCollection($stemAssociableChoices[0]->getContent()));
			$this->assertEquals('STEM_0', $stemAssociableChoices[0]->getIdentifier());
			$this->assertEquals('[Stem 2]', QtiMarshallerUtil::marshallCollection($stemAssociableChoices[1]->getContent()));
			$this->assertEquals('STEM_1', $stemAssociableChoices[1]->getIdentifier());
			$this->assertEquals('[Stem 3]', QtiMarshallerUtil::marshallCollection($stemAssociableChoices[2]->getContent()));
			$this->assertEquals('STEM_2', $stemAssociableChoices[2]->getIdentifier());
			$this->assertEquals('[Stem 4]', QtiMarshallerUtil::marshallCollection($stemAssociableChoices[3]->getContent()));
			$this->assertEquals('STEM_3', $stemAssociableChoices[3]->getIdentifier());
			foreach ($stemAssociableChoices as $choice) {
				$this->assertEquals(1, $choice->getMatchMax());
				$this->assertEquals(1, $choice->getMatchMin());
			}

			// Assert its target choices (options)
			/** @var SimpleAssociableChoice[] $optionAssociableChoices */
			$optionAssociableChoices = $interaction->getTargetChoices()->getSimpleAssociableChoices()->getArrayCopy(true);
			$this->assertEquals('True', QtiMarshallerUtil::marshallCollection($optionAssociableChoices[0]->getContent()));
			$this->assertEquals('OPTION_0', $optionAssociableChoices[0]->getIdentifier());
			$this->assertEquals('False', QtiMarshallerUtil::marshallCollection($optionAssociableChoices[1]->getContent()));
			$this->assertEquals('OPTION_1', $optionAssociableChoices[1]->getIdentifier());
			foreach ($optionAssociableChoices as $choice) {
				$this->assertEquals(4, $choice->getMatchMax());
				$this->assertEquals(1, $choice->getMatchMin());
			}

			/** @var ResponseDeclaration $responseDeclaration */
			$responseDeclaration = $assessmentItem->getResponseDeclarations()->getArrayCopy()[0];
			$this->assertEquals(Cardinality::MULTIPLE, $responseDeclaration->getCardinality());
			$this->assertEquals(BaseType::DIRECTED_PAIR, $responseDeclaration->getBaseType());

			/** @var Value[] $values */
			$values = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
			$this->assertDirectPair($values[0]->getValue(), 'STEM_0', 'OPTION_0');
			$this->assertDirectPair($values[1]->getValue(), 'STEM_1', 'OPTION_1');
			$this->assertDirectPair($values[2]->getValue(), 'STEM_2', 'OPTION_0');
			$this->assertDirectPair($values[3]->getValue(), 'STEM_3', 'OPTION_1');

			$this->assertNull($responseDeclaration->getMapping());
			
			/** @var ResponseProcessing $responseProcessing */
			$responseProcessing = $assessmentItem->getResponseProcessing();
			
			$this->assertCount(2,$assessmentItem->getResponseProcessing()->getComponents());
            $this->assertCount(2, $assessmentItem->getResponseProcessing()->getComponentsByClassName('responseIf', true));
            $responseIf = $assessmentItem->getResponseProcessing()->getComponentsByClassName('responseIf', true)->getArrayCopy()[0];
            $this->assertTrue($responseIf instanceof ResponseIf);
            $promptIfString = QtiMarshallerUtil::marshallCollection($responseIf->getComponents());
            $this->assertEquals('<isNull><variable identifier="RESPONSE"/></isNull><setOutcomeValue identifier="SCORE"><baseValue baseType="float">0</baseValue></setOutcomeValue>', $promptIfString);
            
            $this->assertCount(2, $assessmentItem->getResponseProcessing()->getComponentsByClassName('responseElse', true));
            $responseElse = $assessmentItem->getResponseProcessing()->getComponentsByClassName('responseElse', true)->getArrayCopy()[0];
            $this->assertTrue($responseElse instanceof ResponseElse);
            $promptElseString = QtiMarshallerUtil::marshallCollection($responseElse->getComponents());
            $this->assertEquals('<responseCondition><responseIf><match><variable identifier="RESPONSE"/><correct identifier="RESPONSE"/></match><setOutcomeValue identifier="SCORE"><baseValue baseType="float">1</baseValue></setOutcomeValue></responseIf><responseElse><setOutcomeValue identifier="SCORE"><baseValue baseType="float">0</baseValue></setOutcomeValue></responseElse></responseCondition>', $promptElseString);
            
            $modalFeedBackCollections = $assessmentItem->getModalFeedbacks();
            $this->assertTrue($modalFeedBackCollections instanceof ModalFeedbackCollection);
            foreach($modalFeedBackCollections as $modalFeedback) {
                $this->assertTrue($modalFeedback instanceof ModalFeedback);
                $promptFeedbackString = $modalFeedback->getComponents()[0]->getContent();
                $this->assertEquals('General Feedback', $promptFeedbackString);
            }
		}
    }

    private function assertDirectPair(QtiDirectedPair $pair, $expectedFirstValue, $expectedSecondValue)
    {
        $this->assertEquals($expectedFirstValue, $pair->getFirst());
        $this->assertEquals($expectedSecondValue, $pair->getSecond());
    }
}
