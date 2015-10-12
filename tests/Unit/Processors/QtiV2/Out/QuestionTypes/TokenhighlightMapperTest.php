<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\QuestionTypes\tokenhighlight;
use LearnosityQti\Processors\QtiV2\Out\QuestionTypes\TokenhighlightMapper;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\Hottext;
use qtism\data\content\interactions\HottextInteraction;

class TokenhighlightMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testWithNoValidation()
    {
        $template =
            '<p><span class="lrn_token">Hello</span></p>' .
            '<p><span class="lrn_token">Welcome to my world!</span></p>' .
            '<p><span class="lrn_token">Brown sugar</span></p>';
        $question = new tokenhighlight('tokenhighlight', $template, 'custom');

        $mapper = new TokenhighlightMapper();
        /** @var HottextInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert($question, 'testIdentifier', 'testIdentifierLabel');
        $this->assertTrue($interaction instanceof HottextInteraction);
        $this->assertNull($responseDeclaration);
        $this->assertNull($responseProcessing);

        // Assert those hottext
        /** @var Hottext[] $hottexts */
        $hottexts = $interaction->getComponentsByClassName('hottext', true)->getArrayCopy(true);
        $this->assertEquals(3, $interaction->getComponentsByClassName('hottext', true)->count());
        $this->assertEquals('TOKEN_0', $hottexts[0]->getIdentifier());
        $this->assertEquals('Hello', QtiMarshallerUtil::marshallCollection($hottexts[0]->getComponents()));
        $this->assertEquals('TOKEN_1', $hottexts[1]->getIdentifier());
        $this->assertEquals('Welcome to my world!', QtiMarshallerUtil::marshallCollection($hottexts[1]->getComponents()));
        $this->assertEquals('TOKEN_2', $hottexts[2]->getIdentifier());
        $this->assertEquals('Brown sugar', QtiMarshallerUtil::marshallCollection($hottexts[2]->getComponents()));
    }
}
