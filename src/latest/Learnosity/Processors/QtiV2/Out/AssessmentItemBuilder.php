<?php

namespace Learnosity\Processors\QtiV2\Out;

use Learnosity\Entities\Question;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\Out\Processings\ProcessingInterface;
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

class AssessmentItemBuilder
{
    const MAPPER_CLASS_BASE = 'Learnosity\Processors\QtiV2\Out\QuestionTypes\\';

    /**
     * @var ItemBodyBuilder
     */
    private $itemBodyBuilder;

    public function __construct()
    {
        $this->itemBodyBuilder = new ItemBodyBuilder();
    }

    public function build($itemIdentifier, $itemLabel, array $questions, $content = '')
    {
        // Pre-processing works
        $processings = [];
        foreach ($processings as $processing) {
            /** @var ProcessingInterface $processing */
            $questions = $processing->processQuestions($questions);
            $content = empty($content) ? $content : $processing->processItemContent($content);
        }

        // Initialise our <assessmentItem>
        $assessmentItem = new AssessmentItem($itemIdentifier, $itemIdentifier, false);
        $assessmentItem->setLabel($itemLabel);
        $assessmentItem->setOutcomeDeclarations($this->buildOutcomeDeclarations());
        $assessmentItem->setToolName('Learnosity');

        // Store interactions on this array to later be placed on <itemBody>
        $interactions = [];
        $responseDeclarationCollection = new ResponseDeclarationCollection();
        foreach ($questions as $question) {
            /** @var Question $question */
            // Map the `questions` and its validation objects to be placed at <itemBody>
            list($interaction, $responseDeclaration, $responseProcessing) = $this->map($question);
            if (!empty($responseDeclaration)) {
                $responseDeclarationCollection->attach($responseDeclaration);
            }
            $interactions[$question->get_reference()] = $interaction;
        }

        // Build <itemBody>
        $assessmentItem->setItemBody($this->itemBodyBuilder->buildItemBody($interactions, $content));

        // Map <responseDeclaration>
        if (!empty($responseDeclaration)) {
            $assessmentItem->setResponseDeclarations($responseDeclarationCollection);
        }
        // Map <responseProcessing>
        if (!empty($responseProcessing)) {
            $assessmentItem->setResponseProcessing($responseProcessing);
        }
        return $assessmentItem;
    }

    private function map(Question $question)
    {
        $type = $question->get_type();
        if (!in_array($type, Constants::$supportedQuestionTypes)) {
            throw new MappingException("Question type `$type` not yet supported to be mapped to QTI");
        }
        $clazz = new \ReflectionClass(self::MAPPER_CLASS_BASE . ucfirst($type . 'Mapper'));
        $questionTypeMapper = $clazz->newInstance();

        // Try to use question `reference` as identifier
        // Otherwise, generate an alternative identifier and store the original reference as `label` to be passed in
        $questionReference = $question->get_reference();
        $interactionIdentifier = Format::isIdentifier($questionReference, false) ? $questionReference : strtoupper($type)  . '_' . StringUtil::generateRandomString(12);
        if ($interactionIdentifier !== $questionReference) {
            LogService::log(
                "The question `reference` ($questionReference) is not a valid identifier. " .
                "Replaced it with randomly generated `$interactionIdentifier` and stored the original `reference` as `label` attribute"
            );
        }
        return $questionTypeMapper->convert($question->get_data(), $interactionIdentifier, $questionReference);
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
