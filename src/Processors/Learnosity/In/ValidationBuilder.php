<?php


namespace Learnosity\Processors\Learnosity\In;

class ValidationBuilder
{
    private $scoringType;
    private $responses;
    const BASE_NS = '\\Learnosity\\Entities\\QuestionTypes\\';

    public function __construct($scoringType, array $responses)
    {
        $this->scoringType = $scoringType;
        $this->responses = $responses;
    }

    public function buildValidation($className)
    {
        $validationRef = new \ReflectionClass(self::BASE_NS . $className . '_validation');
        $validation = $validationRef->newInstance();
        $validation->set_scoring_type($this->scoringType);

        $validResponse = null;
        $altResponses = [];

        foreach ($this->responses as $response) {
            if (!$validResponse) {
                $validResponseRef = new \ReflectionClass(self::BASE_NS . $className . '_validation_valid_response');
                $validResponse = $validResponseRef->newInstance();
                $validResponse->set_score($response['score']);
                $validResponse->set_value($response['value']);
            } else {
                $altResponseItemRef = new \ReflectionClass(self::BASE_NS . $className . '_validation_alt_responses_item');
                $altResponseItem = $altResponseItemRef->newInstance();
                $altResponseItem->set_score($response['score']);
                $altResponseItem->set_value($response['value']);
                $altResponses[] = $altResponseItem;
            }
        }

        if ($validResponse) {
            $validation->set_valid_response($validResponse);
        }
        if (!empty($altResponses)) {
            $validation->set_alt_responses($altResponses);
        }

        return $validation;
    }
}
