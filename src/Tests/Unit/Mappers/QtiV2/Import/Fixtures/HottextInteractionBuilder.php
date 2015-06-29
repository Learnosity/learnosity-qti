<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures;

use qtism\data\content\BlockStaticCollection;
use qtism\data\content\InlineCollection;
use qtism\data\content\InlineStaticCollection;
use qtism\data\content\interactions\Hottext;
use qtism\data\content\interactions\HottextInteraction;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\text\P;

class HottextInteractionBuilder
{
    public static function buildSimple($identifier, $contentMap)
    {
        $interactionContent = new BlockStaticCollection();
        $p = new P();
        $pCollection = new InlineCollection();

        foreach ($contentMap as $content) {
            if (is_array($content)) {
                $hottext = new Hottext(key($content));
                $hottextContent = new InlineStaticCollection();
                $hottextContent->attach(new TextRun($content[key($content)]));
                $hottext->setContent($hottextContent);
                $pCollection->attach($hottext);
            } else if (is_string($content)) {
                $pCollection->attach(new TextRun($content));
            }
        }

        $p->setContent($pCollection);
        $interactionContent->attach($p);

        return new HottextInteraction($identifier, $interactionContent);
    }
} 
