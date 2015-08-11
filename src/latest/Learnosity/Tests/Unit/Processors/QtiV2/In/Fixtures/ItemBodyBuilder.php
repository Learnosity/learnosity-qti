<?php

namespace Learnosity\Tests\Unit\Processors\QtiV2\In\Fixtures;


use qtism\data\content\BlockCollection;
use qtism\data\content\InlineCollection;
use qtism\data\content\interactions\InlineInteraction;
use qtism\data\content\ItemBody;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\text\P;
use qtism\data\QtiComponentCollection;

class ItemBodyBuilder {

    public static function buildItemBody(QtiComponentCollection $componentCollection) {
        $itemBody = new ItemBody('testItemBody');
        $blockCollection = new BlockCollection();
        $p = new P();
        $pCollection = new InlineCollection();
        $pCollection->attach(new TextRun('The Matrix movie is starring '));

        // Build the <itemBody>
        $blockCollection->attach($p);

        foreach ($componentCollection as $c) {
            if ($c instanceof InlineInteraction) {
                $pCollection->attach($c);
            } else {
                $blockCollection->attach($c);
            }
        }
        $p->setContent($pCollection);
        $itemBody->setContent($blockCollection);
        return $itemBody;
    }
}
