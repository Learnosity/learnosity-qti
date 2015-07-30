<?php

namespace Learnosity\Processors\QtiV2\Out;

use Learnosity\Entities\Item\item;
use Learnosity\Entities\Question;
use Learnosity\Processors\QtiV2\Out\Builders\AssessmentItemBuilder;
use Learnosity\Services\LogService;
use qtism\common\utils\Format;
use qtism\data\storage\xml\XmlDocument;

class ItemWriter
{
    public function convert(item $item, array $questions)
    {
        // Make sure we clean up the log
        LogService::flush();

        // Try to build the identifier using item `reference`
        // Otherwise, generate an alternative identifier and store the original reference as `label`
        $itemReference = $item->get_reference();
        $itemIdentifier = Format::isIdentifier($itemReference, false) ? $itemReference : 'ITEM_' . StringUtil::generateRandomString(12);
        if ($itemReference !== $itemIdentifier) {
            LogService::log(
                "The item `reference` ($itemReference) is not a valid identifier, thus can not be used for `assessmentItem` identifier. " .
                "Replaced it with randomly generated `$itemIdentifier` and stored the original `reference` as `label` attribute"
            );
        }

        $builder = new AssessmentItemBuilder();
        $assessmentItem = $builder->build($itemIdentifier, $itemReference, $questions, $item->get_content());

        $xml = new XmlDocument();
        $xml->setDocumentComponent($assessmentItem);

        // Flush out all the error messages stored in this static class, also ensure they are unique
        $messages = array_values(array_unique(LogService::flush()));
        return [$xml->saveToString(true), $messages];
    }
}
