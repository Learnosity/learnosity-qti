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
    public function convert(Question $question)
    {
        // Make sure we clean up the log
        LogService::flush();

        // Try to build the identifier using question `reference`
        // Otherwise, generate an alternative identifier and store the original reference as `label`
        $questionReference = $question->get_reference();
        $questionIdentifier = Format::isIdentifier($questionReference, false) ? $questionReference : 'ITEM_' . StringUtil::generateRandomString(12);
        if ($questionReference !== $questionIdentifier) {
            LogService::log(
                "The question `reference` ($questionReference) is not a valid identifier, thus can not be used for `assessmentItem` identifier. " .
                "Replaced it with randomly generated `$questionIdentifier`"
            );
        }

        $itemLabel = (!empty($question->get_item_reference())) ? $question->get_item_reference() : '';

        $builder = new AssessmentItemBuilder();
        $assessmentItem = $builder->build($questionIdentifier, $itemLabel, [$question]);

        $xml = new XmlDocument();
        $xml->setDocumentComponent($assessmentItem);

        $featureBuilderArray = array();
        $featureArray = $question->get_features();

        if (is_array($featureArray) && sizeof($featureArray) > 0) {
            foreach ($featureArray as $feature) {
                if ($feature['data']['type'] == 'sharedpassage') {
                    $featureBuilder = new FeatureItemBuilder();
                    $featureHtml = $featureBuilder->build($feature);
                    $featureBuilderArray[$question->get_reference()] = array($feature['reference'] => $featureHtml);
                } else {
                    $featureBuilderArray['features'] = $feature['reference'];
                }
            }
        }

        // Flush out all the error messages stored in this static class, also ensure they are unique
        $messages = array_values(array_unique(LogService::flush()));
        return [$xml->saveToString(true), $messages, $questionReference, $featureBuilderArray];
    }
}
