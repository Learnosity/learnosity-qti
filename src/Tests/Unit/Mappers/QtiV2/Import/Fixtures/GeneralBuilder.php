<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures;

use qtism\data\content\InlineCollection;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\text\P;

class GeneralBuilder
{
    public static function buildP($textRun)
    {
        $p = new P();
        $pCollection = new InlineCollection();
        $pCollection->attach(new TextRun($textRun));
        $p->setContent($pCollection);
        return $p;
    }
}
