<?php


namespace LearnosityQti\Processors\Learnosity\In\ValidationBuilder;

use LearnosityQti\Services\LogService;

class ValidationBuilder
{
    const BASE_NS = '\\LearnosityQti\\Entities\\QuestionTypes\\';

    public static function build($questionType, $scoringType, array $responses)
    {
        
        
        // Validate the `responses` array
        foreach ($responses as $response) {
            if (!($response instanceof ValidResponse)) {
                throw new \Exception('Invalid `responses` array. Fail to build validation');
            }
        }

        // Filter out negative values
        $responses = array_filter($responses, function ($response) {
            /** @var ValidResponse $response */
            $isPositiveValue = floatval($response->getScore()) >= 0;
            if (!$isPositiveValue) {
                LogService::log('Ignored validation mapping with negative score');
            }
            return $isPositiveValue;
        });

        // Sort by score value, as the one with highest score would be used for `valid_response` object
        self::susort($responses, function ($a, $b) {
            /**
             * @var ValidResponse $a
             * @var ValidResponse $b
             */
            if ($a->getScore() == $b->getScore()) {
                return 0;
            }
            return $a->getScore() > $b->getScore() ? -1 : 1;
        });

        // Build `valid_response` and its `alt_responses`
        $validResponse = null;
        $altResponses = [];

        foreach ($responses as $response) {
            /** @var ValidResponse $response */
            if (!$validResponse) {
                $validResponseRef = new \ReflectionClass(self::BASE_NS . $questionType . '_validation_valid_response');
                $validResponse = $validResponseRef->newInstance();
                $validResponse->set_score($response->getScore());
                if($questionType == 'imageclozeassociationV2') {
                   $convertArray = self::convertArrayIntoArrayOfArray($response->getValue());
                   $validResponse->set_value($convertArray);
                } else {
                    $validResponse->set_value($response->getValue());
                }
            } else {
                $altResponseItemRef = new \ReflectionClass(self::BASE_NS . $questionType . '_validation_alt_responses_item');
                $altResponseItem = $altResponseItemRef->newInstance();
                $altResponseItem->set_score($response->getScore());
                $altResponseItem->set_value($response->getValue());
                $altResponses[] = $altResponseItem;
            }
        }

        // Build dah` validation object
        $validationRef = new \ReflectionClass(self::BASE_NS . $questionType . '_validation');
        $validation = $validationRef->newInstance();
        $validation->set_scoring_type($scoringType);

        if (!empty($validResponse)) {
            $validation->set_valid_response($validResponse);
        }
        if (!empty($altResponses)) {
            $validation->set_alt_responses($altResponses);
        }
        return $validation;
    }

    /**
     * Function to convert array value into array
     *
     * @param type $responseArray response array
     * @return type converted array
     */
    private static function convertArrayIntoArrayOfArray($responseArray){
        foreach($responseArray as $value) {
            $convertArray[] = array($value);
        }
        return $convertArray;
    }

    /**
     * A stable usort function
     * @link https://github.com/vanderlee/PHP-stable-sort-functions/blob/master/functions/susort.php
     */
    private static function susort(array &$array, callable $value_compare_func)
    {
        $index = 0;
        foreach ($array as &$item) {
            $item = [$index++, $item];
        }
        $result = usort($array, function ($a, $b) use ($value_compare_func) {
            $result = $value_compare_func($a[1], $b[1]);
            return $result == 0 ? $a[0] - $b[0] : $result;
        });
        foreach ($array as &$item) {
            $item = $item[1];
        }
        return $result;
    }
}
