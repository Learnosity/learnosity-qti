<?php

namespace LearnosityQti\Processors\QtiV2\Out;

use LearnosityQti\Entities\Question;
use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Utils\StringUtil;
use qtism\common\utils\Format;
use qtism\data\storage\xml\XmlDocument;

class QuestionWriter
{
    public function convert(array $questions)
    {
        // Try to build the identifier using question `reference`
        // Otherwise, generate an alternative identifier and store the original reference as `label`
        $itemReference = $questions[0]->get_item_reference();
        $questionIdentifier = Format::isIdentifier($itemReference, false) ? $itemReference : 'ITEM_' . StringUtil::generateRandomString(12);
        if ($itemReference !== $questionIdentifier) {
            LogService::log(
                "The question `reference` ($itemReference) is not a valid identifier, thus can not be used for `assessmentItem` identifier. " .
                "Replaced it with randomly generated `$questionIdentifier`"
                , 'verbose'
            );
        }

        $itemLabel = (!empty($questions[0]->get_item_reference())) ? $questions[0]->get_item_reference() : '';

        $builder = new AssessmentItemBuilder();
        $assessmentItem = $builder->build($questionIdentifier, $itemLabel, $questions);

        $xml = new XmlDocument();
        $xml->setDocumentComponent($assessmentItem);

        $featureBuilderArray = array();
        $featureArray = $questions[0]->get_features();

        if (is_array($featureArray) && sizeof($featureArray) > 0) {
            foreach ($featureArray as $feature) {
                if ($feature['data']['type'] == 'sharedpassage') {
                    $featureBuilder = new FeatureItemBuilder();
                    $featureHtml = $featureBuilder->build($feature);
                    if (empty($featureBuilderArray[$itemReference])) $featureBuilderArray[$itemReference] = [];
                    $featureBuilderArray[$itemReference][$feature['reference']] = $featureHtml;
                } else {
                    $featureBuilderArray['features'] = $feature['reference'];
                }
            }
        }

        $messages = array_values(array_unique(LogService::read()));
        $xmlString = $xml->saveToString(true);

        return [$xmlString, $messages, $itemReference, $featureBuilderArray];
    }
}
