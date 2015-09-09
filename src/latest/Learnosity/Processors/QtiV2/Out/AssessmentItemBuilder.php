<?php

namespace Learnosity\Processors\QtiV2\Out;

use Learnosity\Entities\Question;
use Learnosity\Exceptions\MappingException;
use Learnosity\Services\LogService;
use Learnosity\Utils\StringUtil;
use qtism\common\enums\BaseType;
use qtism\common\utils\Format;
use qtism\data\AssessmentItem;
use qtism\data\processing\ResponseProcessing;
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
        // Initialise our <assessmentItem>
        $assessmentItem = new AssessmentItem($itemIdentifier, $itemIdentifier, false);
        $assessmentItem->setLabel($itemLabel);
        $assessmentItem->setOutcomeDeclarations($this->buildOutcomeDeclarations());
        $assessmentItem->setToolName('Learnosity');

        // Store interactions on this array to later be placed on <itemBody>
        $interactions = [];
        $responseDeclarationCollection = new ResponseDeclarationCollection();
        $responseProcessingTemplates = [];
        foreach ($questions as $question) {
            /** @var Question $question */
            // Map the `questions` and its validation objects to be placed at <itemBody>
            // The extraContent usually comes from `stimulus` of item that mapped to inline interaction and has no `prompt`
            list($interaction, $responseDeclaration, $responseProcessing, $extraContent) = $this->map($question);
            if (!empty($responseDeclaration)) {
                // TODO: Need to tidy this up
                // Well sometimes we can have multiple response declarations, ie. clozetext
                if ($responseDeclaration instanceof ResponseDeclarationCollection) {
                    $responseDeclarationCollection->merge($responseDeclaration);
                } else {
                    $responseDeclarationCollection->attach($responseDeclaration);
                }
            }
            if (!empty($responseProcessing)) {
                /** @var ResponseProcessing $responseProcessing */
                $responseProcessingTemplates[] = $responseProcessing->getTemplate();
            }
            $interactions[$question->get_reference()]['interaction'] = $interaction;
            if (!empty($extraContent)) {
                $interactions[$question->get_reference()]['extraContent'] = $extraContent;
            }
        }

        // Build <itemBody>
        $assessmentItem->setItemBody($this->itemBodyBuilder->buildItemBody($interactions, $content));

        // Map <responseDeclaration>
        if (!empty($responseDeclarationCollection)) {
            $assessmentItem->setResponseDeclarations($responseDeclarationCollection);
        }
        // Map <responseProcessing> - combine response processing from questions
        // TODO: Tidy up this stuff
        if (!empty($responseProcessingTemplates)) {
            $templates = array_unique($responseProcessingTemplates);
            $isOnlyMatchCorrect = count($templates) === 1 && $templates[0] === Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT;
            $responseProcessing = new ResponseProcessing();
            $responseProcessing->setTemplate($isOnlyMatchCorrect ? Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT : Constants::RESPONSE_PROCESSING_TEMPLATE_MAP_RESPONSE);
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
        $result = $questionTypeMapper->convert($question->get_data(), $interactionIdentifier, $questionReference);
        $result[] = $questionTypeMapper->getExtraContent();
        return $result;
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
