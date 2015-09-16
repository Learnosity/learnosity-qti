<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Entities\QuestionTypes\imageclozeassociation_validation;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\Out\Constants;
use Learnosity\Processors\QtiV2\Out\QuestionTypes\ImageclozeassociationMapper;
use Learnosity\Services\LogService;
use qtism\common\datatypes\DirectedPair;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\processing\ResponseProcessing;
use qtism\data\state\CorrectResponse;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;
use qtism\data\state\ValueCollection;

class ImageclozeassociationValidationBuilder extends AbstractQuestionValidationBuilder
{
    private $possibleResponsesMap;

    public function __construct(array $possibleResponses)
    {
        $this->possibleResponsesMap = array_flip($possibleResponses);
    }

    protected function buildResponseDeclaration($responseIdentifier, $validation)
    {
        $responseDeclaration = new ResponseDeclaration($responseIdentifier);
        $responseDeclaration->setCardinality(Cardinality::MULTIPLE);
        $responseDeclaration->setBaseType(BaseType::DIRECTED_PAIR);

        /** @var imageclozeassociation_validation $validation */
        $validationValues = $validation->get_valid_response()->get_value();
        $validationScore = $validation->get_valid_response()->get_score();

        // Build correct response
        // Try to handle `null` values in `valid_response` `value`s
        $values = new ValueCollection();
        foreach ($validationValues as $index => $validResponse) {
            if (!isset($this->possibleResponsesMap[$validResponse])) {
                throw new MappingException('Invalid or missing valid response `' . $validResponse . '``');
            }
            if (!empty($validResponse)) {
                $first = ImageclozeassociationMapper::ASSOCIABLEHOTSPOT_IDENTIFIER_PREFIX . $index;
                $second = ImageclozeassociationMapper::GAPIMG_IDENTIFIER_PREFIX . $this->possibleResponsesMap[$validResponse];
                $values->attach(new Value(new DirectedPair($first, $second)));
            }
        }
        if ($values->count() > 0) {
            $correctResponse = new CorrectResponse($values);
            $responseDeclaration->setCorrectResponse($correctResponse);

            // Building mapping is too complicated since validation object between QTI and Learnosity is too different
            // So, left this out and always assume we are dealing with `match_correct`
            if (intval($validationScore) !== 1) {
                LogService::log('Mapped with `match_correct` response template even though the validation score is not 1, but ' . $validationScore);
            }
        }
        return $responseDeclaration;
    }


    protected function buildResponseProcessing($validation, $isCaseSensitive = true)
    {
        // TODO: Support for partial match and partial match per response is left out
        // TODO: Check values
        $responseProcessing = new ResponseProcessing();
        $responseProcessing->setTemplate(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT);

        return $responseProcessing;
    }
}
