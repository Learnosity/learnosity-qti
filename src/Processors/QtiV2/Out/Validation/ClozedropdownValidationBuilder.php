<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Entities\QuestionTypes\clozedropdown_validation;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\CorrectResponse;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\ResponseDeclarationCollection;
use qtism\data\state\Value;
use qtism\data\state\ValueCollection;

class ClozedropdownValidationBuilder extends AbstractQuestionValidationBuilder
{
    private $valueIdentifierMapPerInlineChoices;

    public function __construct(array $valueIdentifierMapPerInlineChoices)
    {
        $this->valueIdentifierMapPerInlineChoices = $valueIdentifierMapPerInlineChoices;
    }

    protected function buildResponseDeclaration($responseIdentifier, $validation)
    {
        /** @var clozedropdown_validation $validation */
        // Since we split {{response}} to multiple interactions, so we would have multiple <responseDeclaration> as needed as well
        $responseDeclarationCollection = new ResponseDeclarationCollection();
        foreach ($validation->get_valid_response()->get_value() as $index => $value) {
            $valueIdentifierMap = $this->valueIdentifierMapPerInlineChoices[$index];
            $valueCollection = new ValueCollection();
            $valueCollection->attach(new Value($valueIdentifierMap[$value]));

            // We make assumption about interaction identifier shall always be the appended with index, ie. `_0`
            $responseDeclaration = new ResponseDeclaration($responseIdentifier . '_' . $index);
            $responseDeclaration->setBaseType(BaseType::IDENTIFIER);
            $responseDeclaration->setCardinality(Cardinality::SINGLE);
            $responseDeclaration->setCorrectResponse(new CorrectResponse($valueCollection));
            $responseDeclarationCollection->attach($responseDeclaration);
        }

        return $responseDeclarationCollection;
    }
}
