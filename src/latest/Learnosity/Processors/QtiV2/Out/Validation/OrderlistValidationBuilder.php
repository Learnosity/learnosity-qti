<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Entities\QuestionTypes\orderlist_validation;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\Out\Constants;
use Learnosity\Services\LogService;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\processing\ResponseProcessing;
use qtism\data\state\ResponseDeclaration;

class OrderlistValidationBuilder extends AbstractQuestionValidationBuilder
{
    private $indexIdentifiersMap;

    public function __construct(array $indexIdentifiersMap)
    {
        $this->indexIdentifiersMap = $indexIdentifiersMap;
    }

    protected function buildResponseDeclaration($responseIdentifier, $validation)
    {
        /** @var orderlist_validation $validation */
        if ($validation->get_scoring_type() !== 'exactMatch') {
            // TODO: Need to do more support on
            // TODO: Partial Match, Partial Match per Response, Partial Pairwise per Response
            throw new MappingException('Does not support other scoring type mapping other than `exactNatch`');
        }

        $responseDeclaration = new ResponseDeclaration($responseIdentifier);
        $responseDeclaration->setCardinality(Cardinality::ORDERED);
        $responseDeclaration->setBaseType(BaseType::IDENTIFIER);

        // Remove `alt_responses` because couldn't support responseDeclaration with multiple valid answers
        if (!empty($validation->get_alt_responses())) {
            $validation->set_alt_responses([]);
            LogService::log('Fail to map multiple validation responses for <responseDeclaration>, only use `valid_response`, ignoring `alt_responses`');
        }

        if ($validation->get_valid_response()->get_score() != 1) {
            $validation->get_valid_response()->set_score(1);
            LogService::log('Only support mapping to `matchCorrect` template, thus validation score is changed to 1 and would be mpped to QTI `match_correct.xml` template');
        }

        $correctResponseBuilder = new QtiCorrectResponseBuilder();
        $responseDeclaration->setCorrectResponse($correctResponseBuilder->buildWithBaseTypeIdentifier($validation, $this->indexIdentifiersMap));
        $mappingBuilder = new QtiMappingBuilder();
        $responseDeclaration->setMapping($mappingBuilder->buildWithBaseTypeIdentifier($validation, $this->indexIdentifiersMap));

        return $responseDeclaration;
    }

    protected function buildResponseProcessing($validation, $isCaseSensitive)
    {
        $responseProcessing = new ResponseProcessing();
        $responseProcessing->setTemplate(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT);
        return $responseProcessing;
    }
}
