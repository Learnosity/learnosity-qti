<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\In\Interactions;


use Learnosity\Entities\QuestionTypes\imageclozeassociation;
use Learnosity\Processors\QtiV2\In\Interactions\GraphicGapMatchInteractionMapper;
use Learnosity\Processors\QtiV2\In\Interactions\GraphicOrderInteractionMapper;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use Learnosity\Tests\Unit\Mappers\QtiV2\In\Fixtures\GraphicOrderInteractionBuilder;
use Learnosity\Tests\Unit\Mappers\QtiV2\In\Fixtures\ResponseDeclarationBuilder;
use qtism\data\content\xhtml\Object;
use qtism\data\state\Value;

class GraphicOrderInteractionMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testMatchCorrectValidation()
    {
        $bgObject = new Object('http://img.png', 'image/png');
        $bgObject->setWidth(100);
        $bgObject->setHeight(200);
        $testInteraction = GraphicOrderInteractionBuilder::build(
            'testIdentifier',
            $bgObject,
            [
                'A' => [0, 0, 8],
                'B' => [50, 40, 8]
            ]
        );

        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $validResponseIdentifier = [
            new Value('B'),
            new Value('A'),
        ];
        $responseDeclaration =
            ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier', $validResponseIdentifier);
        $mapper = new GraphicOrderInteractionMapper($testInteraction, $responseDeclaration, $responseProcessingTemplate);
        /** @var imageclozeassociation $q */
        $q = $mapper->getQuestionType();
    }
}
