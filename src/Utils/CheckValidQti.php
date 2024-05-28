<?php

namespace LearnosityQti\Utils;

use LearnosityQti\Utils\General\HtmlHelper;
use LearnosityQti\Utils\General\StringHelper;

class CheckValidQti
{
    public static function isSharedPassage($xmlString)
    {
        return (new General\HtmlHelper)->hasElementWithAttribute($xmlString, 'rubricBlock', 'use', 'sharedstimulus');
    }

    public static function isAssessmentItem($xmlString)
    {
        return HtmlHelper::hasElement($xmlString, 'assessmentItem');
    }

    public static function isAssessmentSection($xmlString)
    {
        return HtmlHelper::hasElement($xmlString, 'assessmentSection');
    }

    // Preprocess XML string here
    public static function preProcessing($xmlString)
    {
        // TODO: Hack custom operator, replace it with ours so we can intercept it
        // $xmlString = str_replace('qti.customOperators.text.StringToNumber', 'Learnosity.Jobs.Common.QtiImport.Paarc.Utils.CustomQti.StringToNumberOperator', $xmlString);
        // $xmlString = str_replace('qti.customOperators.text.stringToNumber', 'Learnosity.Jobs.Common.QtiImport.Paarc.Utils.CustomQti.StringToNumberOperator', $xmlString);
        // $xmlString = str_replace('<mtext></mtext>', '', $xmlString);
        // $xmlString = str_replace('<m:mtext/>', '', $xmlString);
        // $xmlString = str_replace('<mn></mn>', '', $xmlString);
        // $xmlString = str_replace('<mn/>', '', $xmlString);
        // $xmlString = str_replace('<m:mn/>', '', $xmlString);
        // $xmlString = str_replace('<mo/>', '', $xmlString);
        // $xmlString = str_replace('<m:mo/>', '', $xmlString);
        // // TODO: We have another hack here for tables to add bootstrap `table` classes
        // $xmlString = HtmlHelper::eachTags($xmlString, 'table', function (&$tag) {
        //     $tag->class = isset($tag->class) ? 'table table-bordered ' . $tag->class : 'table table-bordered';
        // });
        // // TODO: Hack here now remove everything with visuallyhidden class
        // $xmlString = HtmlHelper::eachTags($xmlString, '.visuallyhidden', function (&$tag) {
        //     $tag->outertext = ''; // Strip the tag
        // });
        // // TODO: From Zac: QC Round 1 finding - Remove random <p> </p> tags
        // $xmlString = HtmlHelper::eachTags($xmlString, 'simpleChoice p', function (&$tag) {
        //     $temp = trim($tag->innertext, " \xC2\xA0\n");
        //     // Not strict equivalence because we want to test INT and strings
        //     if (empty($temp) && $temp != 0) {
        //         $tag->outertext = ''; // Strip the tag
        //     }
        // });
        // // TODO: From Zac: QC Round 1 finding - Trim td on tables
        // $xmlString = HtmlHelper::eachTags($xmlString, 'table td', function (&$tag) {
        //     $tag->innertext = trim($tag->innertext, " \xC2\xA0\n");
        // });

        // Return hacked XML string
        return $xmlString;
    }

    public static function postProcessing(
        $item,
        array $questions,
        $itemTags,
        $convertClozeTextToClozeFormula = null
    ): array {
        // Convert `clozetext` to `clozeformula`
        foreach ($questions as &$question) {
            if ($question['type'] === 'clozetext' &&
                $convertClozeTextToClozeFormula === true &&
                StringHelper::startsWith($item['reference'], 'M')
            ) {
                $clozeFormulaNew = [
                    'is_math' => true,
                    'template' => $question['data']['template'],
                    'type' => 'clozeformula',
                    'response_container' => [
                        'template' => '',
                        'width' => '90px'
                    ],
                    'ui_style' => [
                        'type' => 'no-input-ui'
                    ],
                    'validation' => [
                        'scoring_type' => 'exactMatch',
                        'valid_response' => [
                            'score' => 1,
                            'value' => []
                        ]
                    ],
                    'response_containers' => []
                ];

                foreach ($question['data']['validation']['valid_response']['value'] as $valid) {
                    $clozeFormulaNew['validation']['valid_response']['value'][] = [
                        [
                            'method'  => 'equivSymbolic',
                            'value'   => (string)$valid,
                            'options' => [
                                'inverseResult'           => false,
                                'allowThousandsSeparator' => true,
                                'decimalPlaces'           => 10,
                                'setThousandsSeparator'   => [','],
                                'setDecimalSeparator'     => '.'
                            ]
                        ]
                    ];
                }

                if (!empty($question['data']['stimulus'])) {
                    $clozeFormulaNew['stimulus'] = $question['data']['stimulus'];
                }

                $question['type'] = 'clozeformula';
                $question['data'] = $clozeFormulaNew;
            }
        }

        // Return the updated item and questions
        return [$item, $questions];
    }
}
