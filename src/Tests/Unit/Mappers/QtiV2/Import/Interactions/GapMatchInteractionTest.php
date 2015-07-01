<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import\Interactions;

use Learnosity\Mappers\QtiV2\Import\Interactions\GapMatchInteraction;

class GapMatchInteractionTest extends AbstractInteractionTest
{
    public function testWithNoValidation()
    {
        // TODO: Need to finish
        $this->markTestSkipped('Test this bro!');

        $interaction = $this->buildGapMatchInteraction('identifierOne');
        $mapper = new GapMatchInteraction($interaction);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertEquals('tokenhighlight', $question->get_type());
        $this->assertNull($question->get_validation());
    }

    private function buildGapMatchInteraction($identifier)
    {
        return null;
    }
}
