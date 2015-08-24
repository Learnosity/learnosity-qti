<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Entities\QuestionTypes\tokenhighlight_validation;
use Learnosity\Exceptions\MappingException;
use Learnosity\Services\LogService;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\ResponseDeclaration;

class TokenhighlightValidationBuilder extends AbstractQuestionValidationBuilder
{
    private $indexIdentifierMap;

    public function __construct(array $indexIdentifierMap)
    {
        $this->indexIdentifierMap = $indexIdentifierMap;
    }

    protected function buildResponseDeclaration($responseIdentifier, $validation)
    {
        /** @var tokenhighlight_validation $validation */

        if ($validation->get_scoring_type() !== 'exactMatch') {
            throw new MappingException('Does not support other scoring type mapping other than `exactNatch`');
        }

        // Remove `alt_responses` because couldn't support responseDeclaration with multiple valid answers
        if (!empty($validation->get_alt_responses())) {
            $validation->set_alt_responses([]);
            LogService::log('Fail to map multiple validation responses for `responseDeclaration`, only use `valid_response`, ignoring `alt_responses`');
        }

        $responseDeclaration = new ResponseDeclaration($responseIdentifier, BaseType::IDENTIFIER);
        $answersCount = count($validation->get_valid_response()->get_value());
        $responseDeclaration->setCardinality($answersCount <= 1 ? Cardinality::SINGLE : Cardinality::MULTIPLE);

        $correctResponseBuilder = new QtiCorrectResponseBuilder();
        $responseDeclaration->setCorrectResponse($correctResponseBuilder->buildWithBaseTypeIdentifier($validation, $this->indexIdentifierMap));
        $mappingBuilder = new QtiMappingBuilder();
        $responseDeclaration->setMapping($mappingBuilder->buildWithBaseTypeIdentifier($validation, $this->indexIdentifierMap));

        return $responseDeclaration;
    }
}
