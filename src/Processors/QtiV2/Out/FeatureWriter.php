<?php
namespace LearnosityQti\Processors\QtiV2\Out;

use LearnosityQti\Entities\Feature;
use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\StringUtil;
use qtism\common\utils\Format;
use qtism\data\storage\xml\XmlDocument;

class FeatureWriter
{

    public function convert(array $feature)
    {
        // Try to build the identifier using question `reference`
        // Otherwise, generate an alternative identifier and store the original reference as `label`
        $featureReference = $feature[0]->get_reference();
        $featureIdentifier = Format::isIdentifier($featureReference, false) ? $featureReference : 'ITEM_' . StringUtil::generateRandomString(12);
        if ($featureReference !== $featureIdentifier) {
            LogService::log(
                "The feature `reference` ($featureReference) is not a valid identifier, thus can not be used for `assessmentItem` identifier. " .
                "Replaced it with randomly generated `$featureIdentifier`",
                'verbose'
            );
        }
        $content = $feature[0]->get_content();
        $builder = new AssessmentItemBuilder();
        $assessmentItem = $builder->buildFeature($featureIdentifier, '', $feature, $content);
        $xml = new XmlDocument();
        $xml->setDocumentComponent($assessmentItem);

        // Flush out all the error messages stored in this static class, also ensure they are unique
        $messages = array_values(array_unique(LogService::flush()));
        return [$xml->saveToString(true), $featureReference, $messages, []];
    }
}
