<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\In\Interactions;


use Learnosity\Entities\QuestionTypes\imageclozeassociation;
use Learnosity\Processors\QtiV2\In\Interactions\GraphicOrderInteractionMapper;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use Learnosity\Tests\Unit\Mappers\QtiV2\In\Fixtures\GraphicOrderInteractionBuilder;
use Learnosity\Tests\Unit\Mappers\QtiV2\In\Fixtures\ResponseDeclarationBuilder;
use qtism\data\content\xhtml\Object;

class GraphicOrderInteractionMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleWithNoValidation()
    {
        $this->markTestIncomplete();
    }

    public function testWithMatchCorrectValidation()
    {
        $interactionImage = new Object('http://img.png', 'image/png');
        $interactionImage->setWidth(100);
        $interactionImage->setHeight(200);
        $testInteraction = GraphicOrderInteractionBuilder::build(
            'testIdentifier',
            $interactionImage,
            [
                'A' => [0, 0, 8],
                'B' => [50, 40, 8]
            ]
        );
        $mapper = new GraphicOrderInteractionMapper(
            $testInteraction,
            ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier', ['B', 'A']),
            ResponseProcessingTemplate::matchCorrect()
        );
        /** @var imageclozeassociation $q */
        $questionType = $mapper->getQuestionType();

        $this->assertNotNull($questionType);
        $this->assertInstanceOf('Learnosity\Entities\QuestionTypes\imageclozeassociation', $questionType);

        $this->markTestIncomplete();
    }

    public function testWithMapResponseValidation()
    {
        $this->markTestIncomplete();
    }
}
