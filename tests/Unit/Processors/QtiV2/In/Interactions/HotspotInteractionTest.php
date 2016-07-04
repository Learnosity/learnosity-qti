<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\In\Interactions;

use LearnosityQti\Processors\QtiV2\In\Interactions\HotspotInteractionMapper;
use LearnosityQti\Processors\QtiV2\In\ResponseProcessingTemplate;
use LearnosityQti\Tests\Unit\Processors\QtiV2\In\Fixtures\HotspotInteractionBuilder;
use LearnosityQti\Tests\Unit\Processors\QtiV2\In\Fixtures\ResponseDeclarationBuilder;
use qtism\data\content\interactions\HotspotInteraction;
use qtism\data\content\xhtml\Object;

class HotspotInteractionTest extends AbstractInteractionTest
{
    public function testImageWithoutWidth()
    {
        $this->setExpectedException('LearnosityQti\Exceptions\MappingException');

        $imageObject = new Object('http://anyurl.com', 'image/png');
        $collection = HotspotInteractionBuilder::buildRectShapesChoices();
        $interaction = new HotspotInteraction('thisJustWontWork', $imageObject, 0, $collection);

        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse('thisJustWontWork', ['A']);
        $mapper = new HotspotInteractionMapper($interaction, $responseDeclaration, $responseProcessingTemplate);
        $question = $mapper->getQuestionType();
    }
    
    public function testHotspotWithRectShape()
    {
        $interaction = HotspotInteractionBuilder::buildWithRectShapesChoices('testIdentifier');
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier', ['A']);
        $mapper = new HotspotInteractionMapper($interaction, $responseDeclaration, $responseProcessingTemplate);

        $question = $mapper->getQuestionType();
        $this->assertEquals('hotspot', $question->get_type());
        $this->assertNotEmpty($question->get_area_attributes()->get_global());

        // Assert areas calculation is correct
        $areas = $question->get_areas();
        $this->assertEquals([
            ['x' => 5.34, 'y' => 32.43],
            ['x' => 48.85, 'y' => 32.43],
            ['x' => 48.85, 'y' => 75.68],
            ['x' => 5.34, 'y' => 75.68],
        ], $areas['A']);
        $this->assertEquals([
            ['x' => 50.38, 'y' => 29.73],
            ['x' => 95.42, 'y' => 29.73],
            ['x' => 95.42, 'y' => 81.08],
            ['x' => 50.38, 'y' => 81.08],
        ], $areas['B']);

        // Assert exact match validation
        $validation = $question->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());
        $this->assertEquals(1, $validation->get_valid_response()->get_score());
        $this->assertEquals([0], $validation->get_valid_response()->get_value());
    }

    public function testHotspotWithPolyShape()
    {
        $interaction = HotspotInteractionBuilder::buildWithRectShapesChoices('testIdentifier');
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier', ['A']);
        $mapper = new HotspotInteractionMapper($interaction, $responseDeclaration, $responseProcessingTemplate);

        $question = $mapper->getQuestionType();
        $this->assertEquals('hotspot', $question->get_type());
        $this->assertNotEmpty($question->get_area_attributes()->get_global());

        // Assert areas calculation is correct
        $areas = $question->get_areas();
        $this->assertEquals([
            ['x' => 5.34, 'y' => 32.43],
            ['x' => 48.85, 'y' => 32.43],
            ['x' => 48.85, 'y' => 75.68],
            ['x' => 5.34, 'y' => 75.68],
        ], $areas['A']);
        $this->assertEquals([
            ['x' => 50.38, 'y' => 29.73],
            ['x' => 95.42, 'y' => 29.73],
            ['x' => 95.42, 'y' => 81.08],
            ['x' => 50.38, 'y' => 81.08],
        ], $areas['B']);

        // Assert exact match validation
        $validation = $question->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());
        $this->assertEquals(1, $validation->get_valid_response()->get_score());
        $this->assertEquals([0], $validation->get_valid_response()->get_value());
    }


    public function testHotspotWithCircleShape()
    {
        $interaction = HotspotInteractionBuilder::buildWithCircleShapesChoices('testIdentifier');
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier', ['A']);
        $mapper = new HotspotInteractionMapper($interaction, $responseDeclaration, $responseProcessingTemplate);

        $question = $mapper->getQuestionType();
        $this->assertEquals('hotspot', $question->get_type());
        $this->assertNotEmpty($question->get_area_attributes()->get_global());

        // Assert areas calculation is correct
        $areas = $question->get_areas();
        $this->assertEquals([
            ['x' => 33.5, 'y' => 43.93],
            ['x' => 41.26, 'y' => 43.93],
            ['x' => 41.26, 'y' => 38.21],
            ['x' => 33.5, 'y' => 38.21],
        ], $areas['A']);
        $this->assertEquals([
            ['x' => 53.4, 'y' => 68.57],
            ['x' => 61.17, 'y' => 68.57],
            ['x' => 61.17, 'y' => 62.86],
            ['x' => 53.4, 'y' => 62.86],
        ], $areas['B']);

        // Assert exact match validation
        $validation = $question->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());
        $this->assertEquals(1, $validation->get_valid_response()->get_score());
        $this->assertEquals([0], $validation->get_valid_response()->get_value());
    }
}
