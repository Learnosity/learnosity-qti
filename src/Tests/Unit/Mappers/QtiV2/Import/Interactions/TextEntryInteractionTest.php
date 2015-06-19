<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import\Interactions;

use Learnosity\Mappers\QtiV2\Import\Interactions\TextEntryInteraction;

class TextEntryInteractionTest extends AbstractInteractionTest
{

    /* @var $interaction TextEntryInteraction */
    protected $interaction;


    public function setup()
    {
        parent::setup();
        $mockInteraction = $this->getMockBuilder('qtism\data\content\interactions\Interaction')
            ->disableOriginalConstructor()->getMock();
        $this->interaction = new TextEntryInteraction($mockInteraction);
    }



}
