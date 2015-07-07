<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\In\Fixtures;


use qtism\data\content\BlockStaticCollection;
use qtism\data\content\InlineCollection;
use qtism\data\content\interactions\Gap;
use qtism\data\content\interactions\GapChoiceCollection;
use qtism\data\content\interactions\GapImg;
use qtism\data\content\interactions\GapMatchInteraction;
use qtism\data\content\interactions\GapText;
use qtism\data\content\TextOrVariableCollection;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\Object;
use qtism\data\content\xhtml\text\P;

class GapMatchInteractionBuilder
{
    public static function buildGapMatchInteraction($identifier, array $gapTextList, array $gapImgList, array $gapList)
    {
        $gapChoiceCollection = new GapChoiceCollection();

        foreach ($gapTextList as $identifier => $contentStr) {
            $gapText = new GapText($identifier, 1);
            $content = new TextOrVariableCollection();
            $content->attach(new TextRun($contentStr));
            $gapText->setContent($content);
            $gapChoiceCollection->attach($gapText);
        }

        foreach ($gapImgList as $identifier => $imagedURL) {
            $obj = new Object($imagedURL, 'image/png');
            $gapImg = new GapImg($identifier, 1, $obj);
            $gapChoiceCollection->attach($gapImg);
        }

        $content = new BlockStaticCollection();
        $p = new P();
        $inlineCollection = new InlineCollection();
        foreach ($gapList as $gapIdentifier) {
            $gap = new Gap($gapIdentifier);
            $inlineCollection->attach($gap);
        }
        $p->setContent($inlineCollection);
        $content->attach($p);

        return new GapMatchInteraction($identifier, $gapChoiceCollection, $content);
    }
}
