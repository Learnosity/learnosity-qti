<?php

namespace LearnosityQti\Processors\QtiV2\Out\Validation;

use LearnosityQti\Entities\QuestionTypes\imageclozeassociation_validation;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\Out\QuestionTypes\ImageclozeassociationMapper;
use qtism\common\datatypes\QtiDirectedPair;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
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
        $validationScore = floatval($validation->get_valid_response()->get_score());

        // Build correct response
        // Try to handle `null` values in `valid_response` `value`s
        $values = new ValueCollection();
        $mapEntriesCollection = new MapEntryCollection();
        foreach ($validationValues as $index => $validResponse) {
            if (!isset($this->possibleResponsesMap[$validResponse])) {
                throw new MappingException('Invalid or missing valid response `' . $validResponse . '``');
            }
            if (!empty($validResponse)) {
                $first = ImageclozeassociationMapper::GAPIMG_IDENTIFIER_PREFIX . $this->possibleResponsesMap[$validResponse];
                $second = ImageclozeassociationMapper::ASSOCIABLEHOTSPOT_IDENTIFIER_PREFIX . $index;
                $values->attach(new Value(new QtiDirectedPair($first, $second)));
                $mapEntriesCollection->attach(new MapEntry(new QtiDirectedPair($first, $second), $validationScore));
            }
        }
        if ($values->count() > 0) {
            $correctResponse = new CorrectResponse($values);
            $responseDeclaration->setCorrectResponse($correctResponse);
            $responseDeclaration->setMapping(new Mapping($mapEntriesCollection));
        }
        return $responseDeclaration;
    }
}
