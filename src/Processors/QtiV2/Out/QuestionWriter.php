<?php

namespace Learnosity\Processors\QtiV2\Out;

use Learnosity\Entities\Question;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\Out\QuestionTypes\McqTypeMapper;
use qtism\data\AssessmentItem;
use qtism\data\state\DefaultValue;
use qtism\data\state\OutcomeDeclaration;
use qtism\data\state\OutcomeDeclarationCollection;
use qtism\data\state\ResponseDeclarationCollection;
use qtism\data\state\Value;
use qtism\data\state\ValueCollection;
use qtism\data\storage\xml\XmlDocument;

class QuestionWriter
{
    public function convert(Question $question)
    {
        $assessmentItem = new AssessmentItem($question->get_reference(), $question->get_reference(), false);

        // Set <outcomeDeclaration> with assumption default value is always 0
        $outcomeDeclaration = new OutcomeDeclaration('SCORE');
        $valueCollection = new ValueCollection();
        $valueCollection->attach(new Value(0));
        $outcomeDeclaration->setDefaultValue(new DefaultValue($valueCollection));
        $outcomeDeclarationCollection = new OutcomeDeclarationCollection();
        $outcomeDeclarationCollection->attach($outcomeDeclaration);
        $assessmentItem->setOutcomeDeclarations($outcomeDeclarationCollection);

        // Mapper
        if ($question->get_type() === 'mcq') {
            $questionTypeMapper = new McqTypeMapper();
        } else {
            throw new MappingException('Question type not yet supported to be mapped to QTI');
        }
        $questionType = $question->get_data();
        list($itemBody, $responseProcessing, $responseDeclaration) = $questionTypeMapper->convert($questionType);

        // Map <itemBody>
        $assessmentItem->setItemBody($itemBody);

        // Map <responseProcessing>
        if (!empty($responseProcessing)) {
            $assessmentItem->setResponseProcessing($responseProcessing);
        }

        // Map <responseDeclaration>
        if (!empty($responseDeclaration)) {
            $responseDeclarationCollection = new ResponseDeclarationCollection();
            $responseDeclarationCollection->attach($responseDeclaration);
            $assessmentItem->setResponseDeclarations($responseDeclarationCollection);
        }

        $xml = new XmlDocument();
        $xml->setDocumentComponent($assessmentItem);
        return $xml->saveToString(true);
    }
}
