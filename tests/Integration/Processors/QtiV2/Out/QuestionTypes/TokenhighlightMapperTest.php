<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Processors\QtiV2\Out\Constants;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\content\interactions\Hottext;
use qtism\data\content\interactions\HottextInteraction;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;
use qtism\data\rules\ResponseElse;
use qtism\data\content\ModalFeedbackCollection;
use qtism\data\content\ModalFeedback;
use ReflectionProperty;

class TokenhighlightMapperTest extends AbstractQuestionTypeTest
{
    public function testSimpleCase()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/tokenhighlight.json'), true);
        

	    $assessmentItemArray = $this->convertToAssessmentItem($data);
        /** @var GapMatchInteraction $interaction */
        foreach ($assessmentItemArray as $assessmentItem) {
		
			// Has <hottextInteraction> as the first and only interaction
			/** @var HottextInteraction $interaction */
			$interaction = $assessmentItem->getComponentsByClassName('hottextInteraction', true)->getArrayCopy()[0];
			$this->assertTrue($interaction instanceof HottextInteraction);

			// And its prompt is mapped correctly to item body
			$promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
			$this->assertEquals('Token highlight question', $promptString);

			// Assert we have 3 hottext elements
			/** @var Hottext[] $hottexts */
			$hottexts = $interaction->getComponentsByClassName('hottext', true)->getArrayCopy(true);
			$this->assertEquals(3, $interaction->getComponentsByClassName('hottext', true)->count());
			$this->assertEquals('TOKEN_0', $hottexts[0]->getIdentifier());
			$this->assertEquals('Risus et tincidunt turpis facilisis.', QtiMarshallerUtil::marshallCollection($hottexts[0]->getComponents()));
			$this->assertEquals('TOKEN_1', $hottexts[1]->getIdentifier());
			$this->assertEquals('Curabitur eu nulla justo. Curabitur vulputate ut nisl et bibendum. '
				. 'Nunc diam enim, porta sed eros vitae. dignissim, et tincidunt turpis facilisis.', QtiMarshallerUtil::marshallCollection($hottexts[1]->getComponents()));
			$this->assertEquals('TOKEN_2', $hottexts[2]->getIdentifier());
			$this->assertEquals('Curabitur eu nulla justo. Curabitur vulputate ut nisl et bibendum.', QtiMarshallerUtil::marshallCollection($hottexts[2]->getComponents()));

			// Assert we have the correct response processing template
			$responseProcessingTemplate = $assessmentItem->getResponseProcessing()->getTemplate();
			$this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT, $responseProcessingTemplate);

			// Assert we have the correct response declaration values and map entries
			/** @var ResponseDeclaration $responseDeclaration */
			$responseDeclaration = $assessmentItem->getResponseDeclarations()->getArrayCopy()[0];
			$this->assertEquals(BaseType::IDENTIFIER, $responseDeclaration->getBaseType());
			$this->assertEquals(Cardinality::MULTIPLE, $responseDeclaration->getCardinality());

			/** @var Value[] $values */
			$values = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
			$this->assertEquals('TOKEN_0', $values[0]->getValue());
			$this->assertEquals('TOKEN_2', $values[1]->getValue());

			$this->assertNull($responseDeclaration->getMapping());
		}
    }
	
	public function testWithDistractorRationale()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/token_highlight_metadata.json'), true);
        

	    $assessmentItemArray = $this->convertToAssessmentItem($data);
        /** @var GapMatchInteraction $interaction */
        foreach ($assessmentItemArray as $assessmentItem) {
		
			// Has <hottextInteraction> as the first and only interaction
			/** @var HottextInteraction $interaction */
			$interaction = $assessmentItem->getComponentsByClassName('hottextInteraction', true)->getArrayCopy()[0];
			$this->assertTrue($interaction instanceof HottextInteraction);

			// And its prompt is mapped correctly to item body
			$promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
			$this->assertEquals('Token highlight question', $promptString);

			// Assert we have 3 hottext elements
			/** @var Hottext[] $hottexts */
			$hottexts = $interaction->getComponentsByClassName('hottext', true)->getArrayCopy(true);
			$this->assertEquals(3, $interaction->getComponentsByClassName('hottext', true)->count());
			$this->assertEquals('TOKEN_0', $hottexts[0]->getIdentifier());
			$this->assertEquals('Risus et tincidunt turpis facilisis.', QtiMarshallerUtil::marshallCollection($hottexts[0]->getComponents()));
			$this->assertEquals('TOKEN_1', $hottexts[1]->getIdentifier());
			$this->assertEquals('Curabitur eu nulla justo. Curabitur vulputate ut nisl et bibendum. '
				. 'Nunc diam enim, porta sed eros vitae. dignissim, et tincidunt turpis facilisis.', QtiMarshallerUtil::marshallCollection($hottexts[1]->getComponents()));
			$this->assertEquals('TOKEN_2', $hottexts[2]->getIdentifier());
			$this->assertEquals('Curabitur eu nulla justo. Curabitur vulputate ut nisl et bibendum.', QtiMarshallerUtil::marshallCollection($hottexts[2]->getComponents()));

			// Assert we have the correct response declaration values and map entries
			/** @var ResponseDeclaration $responseDeclaration */
			$responseDeclaration = $assessmentItem->getResponseDeclarations()->getArrayCopy()[0];
			$this->assertEquals(BaseType::IDENTIFIER, $responseDeclaration->getBaseType());
			$this->assertEquals(Cardinality::MULTIPLE, $responseDeclaration->getCardinality());

			/** @var Value[] $values */
			$values = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
			$this->assertEquals('TOKEN_0', $values[0]->getValue());
			$this->assertEquals('TOKEN_2', $values[1]->getValue());

			$this->assertNull($responseDeclaration->getMapping());
			
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
                $this->assertEquals('general feedback', $promptFeedbackString);
            }
		}
    }
}
