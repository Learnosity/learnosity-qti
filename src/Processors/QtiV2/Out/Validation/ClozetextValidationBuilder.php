<?php

namespace LearnosityQti\Processors\QtiV2\Out\Validation;

use LearnosityQti\Entities\QuestionTypes\clozetext_validation;
use LearnosityQti\Entities\QuestionTypes\clozetext_validation_alt_responses_item;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\CorrectResponse;
use qtism\data\state\MapEntry;
use qtism\data\state\MapEntryCollection;
use qtism\data\state\Mapping;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\ResponseDeclarationCollection;
use qtism\data\state\Value;
use qtism\data\state\ValueCollection;

class ClozetextValidationBuilder extends AbstractQuestionValidationBuilder
{
    private $isCaseSensitive;

    public function __construct($isCaseSensitive)
    {
        $this->isCaseSensitive = $isCaseSensitive;
    }

    protected function buildResponseDeclaration($responseIdentifier, $validation)
    {
        /** @var clozetext_validation $validation */
        // Since we split {{response}} to multiple interactions, so we would have multiple <responseDeclaration> as needed as well
        $responseDeclarationCollection = new ResponseDeclarationCollection();

        // Process `valid_response`
        foreach ($validation->get_valid_response()->get_value() as $index => $value) {
            // We make assumption about interaction identifier shall always be the appended with index, ie. `_0`
            $responseDeclaration = new ResponseDeclaration($responseIdentifier . '_' . $index);
            $responseDeclaration->setCardinality(Cardinality::SINGLE);
            $responseDeclaration->setBaseType(BaseType::STRING);

            $valueCollection = new ValueCollection();
            $valueCollection->attach(new Value($value));

            $validResponseScore = floatval($validation->get_valid_response()->get_score());
            $mapEntriesCollection = new MapEntryCollection();
            $mapEntriesCollection->attach(new MapEntry($value, $validResponseScore, $this->isCaseSensitive));

            if (!empty($validation->get_alt_responses())) {
                /** @var clozetext_validation_alt_responses_item $alt */
                foreach ($validation->get_alt_responses() as $alt) {
                    // Assuming
                    if (!is_null($alt->get_value()) && isset($alt->get_value()[$index])) {
                        $alternativeValue = $alt->get_value()[$index];
                        $alternativeScore = floatval($alt->get_score());
                        $valueCollection->attach(new Value($alternativeValue));
                        $mapEntriesCollection->attach(new MapEntry($alternativeValue, $alternativeScore, $this->isCaseSensitive));
                    }
                }
            }

            $responseDeclaration->setCorrectResponse(new CorrectResponse($valueCollection));
            $responseDeclaration->setMapping(new Mapping($mapEntriesCollection));
            $responseDeclarationCollection->attach($responseDeclaration);
        }

        return $responseDeclarationCollection;
    }
}
