<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\QuestionTypes\audioplayer;
use LearnosityQti\Processors\QtiV2\Out\QuestionTypes\AudioplayerMapper;
use qtism\data\content\interactions\MediaInteraction;

class AudioFeatureMapperTest extends \PHPUnit_Framework_TestCase {

    public function testSimpleCase()
    {
        $feature_type = 'audioplayer';
		$src = realpath($_SERVER["DOCUMENT_ROOT"]).'/Fixtures/assets/ef04cc3_f589cd84-c67f-4d34-bc1e-2367c64a797e.mp3';
		$feature = new audioplayer($feature_type, $src);
        $feature->set_playback_limit(5);
		
	    $audioPlayerMapper = new AudioplayerMapper();
		list($interaction, $responseDeclaration, $responseProcessing) = $audioPlayerMapper->convert($feature, 'testIdentifier', 'testIdentifierLabel');
        
        // Check usual
        $this->assertTrue($interaction instanceof MediaInteraction);
        $this->assertEquals('testIdentifier', $interaction->getResponseIdentifier());
        $this->assertEquals('testIdentifierLabel', $interaction->getLabel());
		$this->assertEquals('5', $interaction->getMaxPlays());
        $this->assertNotNull($responseDeclaration);
        $this->assertNull($responseProcessing);
    }
}
