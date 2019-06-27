<?php

namespace LearnosityQti\Processors\QtiV2\Out;

use LearnosityQti\Entities\Question;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Utils\StringUtil;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
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
    const MAPPER_CLASS_BASE = 'LearnosityQti\Processors\QtiV2\Out\QuestionTypes\\';

    /**
     * @var ItemBodyBuilder
     */
    private $itemBodyBuilder;

    public function __construct()
    {
        $this->itemBodyBuilder = new ItemBodyBuilder();
        
        // to add multiple outcomedeclaration in case of feedback
        $this->outcomeDeclarationCollection = new OutcomeDeclarationCollection();
    }

    public function build($itemIdentifier, $itemLabel, array $questions, $content = '')
    {
        // Initialise our <assessmentItem>
        $assessmentItem = new AssessmentItem($itemIdentifier, $itemIdentifier, false);
        $assessmentItem->setLabel($itemLabel);
        $assessmentItem->setToolName('Learnosity');

        // Store interactions on this array to later be placed on <itemBody>
        $interactions = [];
        $responseDeclarationCollection = new ResponseDeclarationCollection();
        $responseProcessingTemplates = [];
        foreach ($questions as $question) {
            $questionData = $question->to_array();
            if (isset($questionData['data']['validation']['valid_response']['score'])) {
                $score = $questionData['data']['validation']['valid_response']['score'];
                $assessmentItem->setOutcomeDeclarations($this->buildOutcomeDeclarations($score));
            } else {
                $assessmentItem->setOutcomeDeclarations($this->buildOutcomeDeclarations(0));
            }
            if (isset($questionData['data']['metadata']['distractor_rationale_response_level'])) {
                $assessmentItem->setOutcomeDeclarations($this->buildFeedbackOutcomeDeclarations());
            }
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
            if (!empty($responseProcessingTemplates[0])) {
                $templates = array_unique($responseProcessingTemplates);
                $isOnlyMatchCorrect = count($templates) === 1 && $templates[0] === Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT;
                $responseProcessing = new ResponseProcessing();
                $responseProcessing->setTemplate($isOnlyMatchCorrect ? Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT : Constants::RESPONSE_PROCESSING_TEMPLATE_MAP_RESPONSE);
                $assessmentItem->setResponseProcessing($responseProcessing);
            } else {
                $assessmentItem->setResponseProcessing($responseProcessing);
            }
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
        $questionReference = $question->get_reference();
        $interactionIdentifier = 'RESPONSE';
        $result = $questionTypeMapper->convert($question->get_data(), $interactionIdentifier, $questionReference);
        $result[] = $questionTypeMapper->getExtraContent();
        return $result;
    }

    private function buildOutcomeDeclarations($score)
    {
        // Set <outcomeDeclaration> with assumption default value is always 0
        $outcomeDeclaration = new OutcomeDeclaration('SCORE', BaseType::FLOAT);
        $valueCollection = new ValueCollection();
        $valueCollection->attach(new Value($score));
        $outcomeDeclaration->setDefaultValue(new DefaultValue($valueCollection));
        
        $outcomeDeclarationCollection = $this->outcomeDeclarationCollection;
        $outcomeDeclarationCollection->attach($outcomeDeclaration);
        return $outcomeDeclarationCollection;
    }

    private function buildFeedbackOutcomeDeclarations()
    {
        // Set <outcomeDeclaration> with  FEEDBACK identifier
        $outcomeDeclaration = new OutcomeDeclaration('FEEDBACK', BaseType::IDENTIFIER, $cardinality = Cardinality::MULTIPLE);
        $outcomeDeclarationCollection = $this->outcomeDeclarationCollection;
        $outcomeDeclarationCollection->attach($outcomeDeclaration);
        return $outcomeDeclarationCollection;
    }
}
