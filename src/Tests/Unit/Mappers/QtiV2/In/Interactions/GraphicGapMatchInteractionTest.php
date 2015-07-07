<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\In\Interactions;

use Learnosity\Entities\QuestionTypes\imageclozeassociation;
use Learnosity\Processors\QtiV2\In\Interactions\GraphicGapMatchInteractionMapper;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use Learnosity\Tests\Unit\Mappers\QtiV2\In\Fixtures\GraphicGapInteractionBuilder;
use Learnosity\Tests\Unit\Mappers\QtiV2\In\Fixtures\ResponseDeclarationBuilder;
use qtism\data\content\xhtml\Object;

class GraphicGapMatchInteractionTest extends \PHPUnit_Framework_TestCase
{

    public function testMapResponseValidation()
    {

        $bgObject = new Object('http://img.png', 'image/png');
        $bgObject->setWidth(100);
        $bgObject->setHeight(200);
        $testInteraction = GraphicGapInteractionBuilder::build(
            'testInteraction',
            $bgObject,
            [
                'A' => 'img_A.png',
                'B' => 'img_B.png',
                'C' => 'img_C.png',
            ],
            [
                'G1' => [0, 0, 10, 10],
                'G2' => [30, 40, 50, 60]
            ]
        );

        $responseProcessingTemplate = ResponseProcessingTemplate::mapResponse();
        $validResponseIdentifier = [
            'A G1' => [1, false],
            'B G1' => [2, false],
            'C G2' => [3, false]
        ];
        $responseDeclaration = ResponseDeclarationBuilder::buildWithMapping(
            'testIdentifier',
            $validResponseIdentifier,
            'DirectedPair'
        );

        $mapper = new GraphicGapMatchInteractionMapper($testInteraction, $responseDeclaration, $responseProcessingTemplate);
        /** @var imageclozeassociation $q */
        $q = $mapper->getQuestionType();
      //  die;
    }
}
