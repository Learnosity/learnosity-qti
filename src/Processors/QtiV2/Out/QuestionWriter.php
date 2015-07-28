<?php

namespace Learnosity\Processors\QtiV2\Out;

use Learnosity\Entities\Question;
use Learnosity\Exceptions\MappingException;
use Learnosity\Services\LogService;
use Learnosity\Utils\StringUtil;
use qtism\common\enums\BaseType;
use qtism\common\utils\Format;
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
    const MAPPER_CLASS_BASE = 'Learnosity\Processors\QtiV2\Out\QuestionTypes\\';

    private $supportedQuestionTypes = [
        'mcq',
    ];

    public function convert(Question $question)
    {
        // Make sure we clean up the log
        LogService::flush();

        // Try to build the identifier using question `reference`
        $assessmentItem = $this->buildAssessmentItemWithIdentifier($question->get_reference(), $question->get_type());
        $assessmentItem->setOutcomeDeclarations($this->buildOutcomeDeclarations());
        $assessmentItem->setToolName('Learnosity');

        // Mapper
        $type = $question->get_type();
        if (!in_array($type, $this->supportedQuestionTypes)) {
            throw new MappingException('Question type not yet supported to be mapped to QTI');
        }
        $clazz = new \ReflectionClass(self::MAPPER_CLASS_BASE . ucfirst($type . 'Mapper'));
        $questionTypeMapper = $clazz->newInstance();
        list($itemBody, $responseDeclaration, $responseProcessing) = $questionTypeMapper->convert($question->get_data());

        // Map <itemBody>
        $assessmentItem->setItemBody($itemBody);

        // Map <responseDeclaration>
        if (!empty($responseDeclaration)) {
            $responseDeclarationCollection = new ResponseDeclarationCollection();
            $responseDeclarationCollection->attach($responseDeclaration);
            $assessmentItem->setResponseDeclarations($responseDeclarationCollection);
        }

        // Map <responseProcessing>
        if (!empty($responseProcessing)) {
            $assessmentItem->setResponseProcessing($responseProcessing);
        }

        $xml = new XmlDocument();
        $xml->setDocumentComponent($assessmentItem);

        // Flush out all the error messages stored in this static class, also ensure they are unique
        $messages = array_values(array_unique(LogService::flush()));
        return [$xml->saveToString(true), $messages];
    }

    private function buildAssessmentItemWithIdentifier($questionReference, $questionType)
    {
        // Use existing question `reference` if it was a valid one
        if (Format::isIdentifier($questionReference, false)) {
            return new AssessmentItem($questionReference, $questionReference, false);
        }
        // Otherwise, generate an alternative identifier and store the original reference as `label`
        $alternativeIdentifier = $questionType . '_' . StringUtil::generateRandomString(12);
        LogService::log(
            "Question `reference` ($questionReference) can is not a valid identifier. " .
            "Replaced it with randomly generated `$alternativeIdentifier` and stored the original `reference` as `label` attribute"
        );
        $assessmentItem = new AssessmentItem($alternativeIdentifier, $alternativeIdentifier, false);
        $assessmentItem->setLabel($questionReference);
        return $assessmentItem;
    }

    private function buildOutcomeDeclarations()
    {
        // Set <outcomeDeclaration> with assumption default value is always 0
        $outcomeDeclaration = new OutcomeDeclaration('SCORE', BaseType::INTEGER);
        $valueCollection = new ValueCollection();
        $valueCollection->attach(new Value(0));
        $outcomeDeclaration->setDefaultValue(new DefaultValue($valueCollection));
        $outcomeDeclarationCollection = new OutcomeDeclarationCollection();
        $outcomeDeclarationCollection->attach($outcomeDeclaration);
        return $outcomeDeclarationCollection;
    }
}
