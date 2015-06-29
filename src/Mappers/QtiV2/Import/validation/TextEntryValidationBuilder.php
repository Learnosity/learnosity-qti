<?php
namespace Learnosity\Mappers\QtiV2\Import\Validation;

use Learnosity\Entities\QuestionTypes\clozetext_validation;
use Learnosity\Entities\QuestionTypes\clozetext_validation_alt_responses_item;
use Learnosity\Entities\QuestionTypes\clozetext_validation_valid_response;

class TextEntryValidationBuilder
{

    public function buildValidation(array $originalResponses)
    {
        $validResponse = new clozetext_validation_valid_response();

        $altResponses = [];
        $validation = null;

        if (count($originalResponses) > 0) {
            for ($i = 0; $i < count($originalResponses); $i++) {
                $scoreGlobal = 0;
                $value = [];
                foreach ($originalResponses[$i] as $answer => $score) {
                    $value[] = $answer;
                    $scoreGlobal += $score;
                }
                if ($i === 0) {
                    $validResponse = new clozetext_validation_valid_response();
                    $validResponse->set_value($value);
                    $validResponse->set_score($scoreGlobal);
                } else {
                    $altResponse = new clozetext_validation_alt_responses_item();
                    $altResponse->set_value($value);
                    $altResponse->set_score($scoreGlobal);
                    $altResponses[] = $altResponse;
                }
            }

        }

        if ($validResponse) {
            $validation = new clozetext_validation();
            $validation->set_scoring_type('exactMatch');
            $validation->set_valid_response($validResponse);
        }

        if ($altResponses && $validation) {
            $validation->set_alt_responses($altResponses);
        }
        return $validation;
    }
}
