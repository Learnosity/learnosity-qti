<?php

namespace LearnosityQti\Processors\QtiV2\Out\Validation;

use LearnosityQti\Entities\QuestionTypes\mcq_validation;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\Out\ResponseDeclarationBuilders\QtiCorrectResponseBuilder;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\CorrectResponse;
use qtism\data\state\MapEntry;
use qtism\data\state\MapEntryCollection;
use qtism\data\state\Mapping;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;
use qtism\data\state\ValueCollection;

class McqValidationBuilder extends AbstractQuestionValidationBuilder
{
    private $isMultipleResponse;
    private $valueIdentifierMap;
    private $question;

    public function __construct($isMultipleResponse, $valueIdentifierMap, $question)
    {
        $this->isMultipleResponse = $isMultipleResponse;
        $this->valueIdentifierMap = $valueIdentifierMap;
        $this->question = $question;
    }

    protected function buildResponseDeclaration($responseIdentifier, $validation)
    {
        /** @var mcq_validation $validation */
        $responseDeclaration = new ResponseDeclaration($responseIdentifier);
        $responseDeclaration->setCardinality($this->isMultipleResponse ? Cardinality::MULTIPLE : Cardinality::SINGLE);
        $responseDeclaration->setBaseType(BaseType::IDENTIFIER);

        $correctResponseBuilder = new QtiCorrectResponseBuilder();
        $responseDeclaration->setCorrectResponse($correctResponseBuilder->buildWithBaseTypeIdentifier($validation, $this->valueIdentifierMap));

        $validationScoringType = $validation->get_scoring_type();
        $validationValues = $validation->get_valid_response()->get_value();
        $validationScore = $validation->get_valid_response()->get_score();
        $values = new ValueCollection();
        $mapEntriesCollection = new MapEntryCollection();

        foreach ($validationValues as $validResponse) {
            foreach ($this->question->get_options() as $i => $option) {
                if ($option->get_value() === $validResponse) {
                    $indexedValues = array_values($this->valueIdentifierMap);
                    $responseId = $indexedValues[$i];
                    break;
                }
            }

            $values->attach(new Value($responseId));

            // Calculate score (adjust for partial scoring)
            $score = is_float($validationScore) ? $validationScore : floatval($validationScore);
            if ($validationScoringType === 'partialMatchV2') {
                $score = floatval($validationScore / count($validationValues));
            }

            $mapEntriesCollection->attach(new MapEntry($responseId, $score));
        }

        if ($values->count() > 0) {
            $correctResponse = new CorrectResponse($values);
            $responseDeclaration->setCorrectResponse($correctResponse);
            $responseDeclaration->setMapping(new Mapping($mapEntriesCollection, 0.0));
        }

        return $responseDeclaration;
    }
}
