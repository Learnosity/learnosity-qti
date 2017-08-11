<?php

namespace LearnosityQti\Utils;

use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\AssumptionHandler;
use LearnosityQti\Utils\General\StringHelper;
use qtism\data\AssessmentItem;
use qtism\data\processing\ResponseProcessing;
use qtism\data\rules\ResponseRuleCollection;

class ResponseProcessingHandler
{
    private static $knownInternalTemplates = [
        '2+A1+E' => 'per-question', // If A is not attempted supposed to have 0 score but we cant handle that, so just assume its per-questions oh well ~
        '1A1+E' => 'per-question',
        'Grouping' => 'per-question', // If all correct score is 2, 50% correct is 1, else 0
        'Paired' => 'per-question', // For pairs scoring calculations - If all correct score is 2, 50% correct is 1, else 0
        'abbi-score-ebsr' => 'dependent', // Need A correct, to got full score
        '3PartEBSR_2A' => 'dependent', // 3 Questions, A+B+C correct score is 2, A+B correct score is 1, else 0
        'Summary' => 'per-question', // If all correct score is 2, 50% correct is 1, else 0
        'FIB_ExactAns' => 'per-question',
        'DEPENDENT-CustomOutlier' => 'per-question', // The template name is dependent, but the logic seems to be per-question. Oh well, how am I not surprised?
    ];

    private static $knownInternalTemplateLocations = [
        'abbi-score-custom' => 'per-question', // Dont know just assume this is per question get Client to check
    ];

    // Dodgy $xmlString just use to check things ofc this is dodgy but what can i do?
    public static function handle(AssessmentItem $assessmentItem, $xmlString)
    {
        $assumedItemScoringType = null;

        /** @var ResponseProcessing $responseProcessing */
        $responseProcessing = $assessmentItem->getResponseProcessing();

        if (!isset($responseProcessing)) {
            // No response processing, skip
            // MS: could be something like extended response
            return [
                [],
                null,
                null
            ];
        }

        // Check known template location
        $templateLocation = $responseProcessing->getTemplateLocation();
        if (!empty($templateLocation)) {
            if (in_array($templateLocation, array_keys(self::$knownInternalTemplateLocations))) {
                $assumedItemScoringType = self::$knownInternalTemplateLocations[$templateLocation];
                $responseProcessing = self::cleanUpResponseProcessing($responseProcessing, $xmlString);
                AssumptionHandler::log('Check response processing - abbi-score-custom - too custom to parse automatically');
                return [$responseProcessing, $assumedItemScoringType];
            }
        }

        // Check known template
        $template = $responseProcessing->getTemplate();
        if (empty($template)) {
            // Assume item scoring mechanism, before the response rules set to null
            $assumedItemScoringType = ItemScoringGuesser::guessWithRules($assessmentItem);
            $responseProcessing = self::cleanUpResponseProcessing($responseProcessing, $xmlString);
        } elseif ($responseProcessing->getResponseRules()->count() > 0) {
            if (in_array($template, array_keys(self::$knownInternalTemplates))) {
                $assumedItemScoringType = self::$knownInternalTemplates[$template];
                $responseProcessing = self::cleanUpResponseProcessing($responseProcessing, $xmlString);
            } else {
                die('New type! ' . $template . PHP_EOL);
            }
        }

        $messages = array_unique(array_values(LogService::flush()));

        return [$responseProcessing, $assumedItemScoringType, $messages];
    }

    private static function cleanUpResponseProcessing(ResponseProcessing $responseProcessing, $xmlString)
    {
        // Assume question scoring mechanism
        $hasCorrectResponse = StringHelper::contains($xmlString, '<correctResponse');
        $hasMapping = StringHelper::contains($xmlString, '<mapping');

        $responseProcessing->setResponseRules(new ResponseRuleCollection());
        // TODO: Need check if mapping value is more than 1
        if ($hasCorrectResponse) {
            $responseProcessing->setTemplate("http://www.imsglobal.org/question/qti_v2p1/rptemplates/match_correct");
        } elseif ($hasMapping) {
            echo ' - Map response only ??? Boo' . PHP_EOL;
            $responseProcessing->setTemplate("http://www.imsglobal.org/question/qti_v2p1/rptemplates/map_response");
        } else {
            // No validation rule? Dont worry about this! ~
            if (StringHelper::contains($xmlString, '<extendedTextInteraction')) {
                AssumptionHandler::log('Empty response processing.');
            }
        }
        return $responseProcessing;
    }
}
