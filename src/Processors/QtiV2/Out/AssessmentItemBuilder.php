<?php
namespace LearnosityQti\Processors\QtiV2\Out;

use LearnosityQti\Entities\Feature;
use LearnosityQti\Entities\Question;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\Out\Validation\featureValidationBuilder;
use LearnosityQti\Services\ConvertToQtiService;
use LearnosityQti\Utils\MimeUtil;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\AssessmentItem;
use qtism\data\content\FlowStaticCollection;
use qtism\data\content\interactions\MediaInteraction;
use qtism\data\content\ModalFeedback;
use qtism\data\content\ModalFeedbackCollection;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\ObjectElement;
use qtism\data\expressions\BaseValue;
use qtism\data\expressions\Correct;
use qtism\data\expressions\ExpressionCollection;
use qtism\data\expressions\operators\AndOperator;
use qtism\data\expressions\operators\Equal;
use qtism\data\rules\ResponseCondition;
use qtism\data\rules\ResponseElse;
use qtism\data\rules\ResponseIf;
use qtism\data\rules\ResponseRuleCollection;
use qtism\data\rules\SetOutcomeValue;
use qtism\data\expressions\Variable;
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
    private $outcomeDeclarationCollection;

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

        $learnosityService = ConvertToQtiService::getInstance();
        $format = $learnosityService->getFormat();

        // Store interactions on this array to later be placed on <itemBody>
        $interactions = [];
        $identifiers = [];
        $totalScore = 0;
        $responseDeclarationCollection = new ResponseDeclarationCollection();
        $responseProcessingTemplates = [];
        $index = 0;

        foreach ($questions as $question) {
            // The index is only relevant for composite items
            if (count($questions) > 1) $index++;

            $questionData = $question->to_array();

            if (!empty($questionData['features']) && in_array($questionData['features'][0]['data']['type'], ['audioplayer', 'videoplayer'])) {
                list($mediaInteraction, $mediaResponseDeclaration) = $this->buildMediaInteraction($questionData);
                if (isset($mediaInteraction)) {
                    $interactions[$questionData['features'][0]['reference']]['interaction'] = $mediaInteraction;
                }
            }

            $content = $questionData['content'];
            $questionType = $questionData['type'];
            $assessmentItem->setOutcomeDeclarations($this->buildScoreOutcomeDeclarations(0, 'SCORE'));

            // add outcome declaration for MAXSCORE
            $max_score = null;
            if (isset($questionData['data']['validation']['max_score'])) {
                $max_score = $questionData['data']['validation']['max_score'];
            } else if (isset($questionData['data']['validation']['valid_response']['score'])) {
                $max_score = $questionData['data']['validation']['valid_response']['score'];
            }
            if ($max_score > 1) {
                $assessmentItem->setOutcomeDeclarations($this->buildScoreOutcomeDeclarations($max_score, 'MAXSCORE'));
            }
            $totalScore += $max_score;

            // add outcome declaration for MINSCORE
            if (isset($questionData['data']['validation']['min_score_if_attempted'])) {
                $min_score = $questionData['data']['validation']['min_score_if_attempted'];
                $assessmentItem->setOutcomeDeclarations($this->buildScoreOutcomeDeclarations($min_score, 'MINSCORE'));
            }

            $baseType = $this->getBaseType($questionData['type'] && $questionType=='mcq');

            if (isset($questionData['data']['metadata']['distractor_rationale_response_level'])) {
                $assessmentItem->setOutcomeDeclarations($this->buildFeedbackOutcomeDeclarations('FEEDBACK', Cardinality::MULTIPLE, $baseType));
            }

            // add outcome declaration for <modalFeedback>
            if (isset($questionData['data']['metadata']['distractor_rationale'])) {
                $distractorRational = $questionData['data']['metadata']['distractor_rationale'];
                $assessmentItem->setOutcomeDeclarations($this->buildFeedbackOutcomeDeclarations('FEEDBACK_GENERAL'));
                $assessmentItem->setModalFeedbacks(new ModalFeedbackCollection(array($this->buildModalFeedBack($distractorRational, 'FEEDBACK_GENERAL', 'correctOrIncorrect'))));
            }

            /** @var Question $question */
            // Map the `questions` and its validation objects to be placed at <itemBody>
            // The extraContent usually comes from `stimulus` of item that mapped to inline interaction and has no `prompt`
            list($interaction, $responseDeclaration, $responseProcessing, $extraContent) = $this->map($question, $index);
            if (!empty($responseDeclaration)) {
                if ($responseDeclaration instanceof ResponseDeclarationCollection && $responseDeclaration->count() > 0) {
                    for ($i = 1; $i <= sizeof($responseDeclaration); $i++) {
                        $assessmentItem->setOutcomeDeclarations($this->buildScoreOutcomeDeclarations(0.0, 'SCORE' . $i));
                    }
                    $responseDeclarationCollection->merge($responseDeclaration);
                } else {
                    $responseDeclarationCollection->attach($responseDeclaration);
                }

                if (isset($mediaResponseDeclaration) && $format != 'canvas') {
                    $responseDeclarationCollection->attach($mediaResponseDeclaration);
                }
            }
            if (!empty($responseProcessing)) {
                /** @var ResponseProcessing $responseProcessing */
                $responseProcessingTemplates[] = $responseProcessing->getTemplate();
            }
            $interactions[$question->get_reference()]['interaction'] = $interaction;
            if (method_exists($interaction, 'getResponseIdentifier')) {
                $identifiers[] = $interaction->getResponseIdentifier();
            }
            if (!empty($extraContent)) {
                $interactions[$question->get_reference()]['extraContent'] = $extraContent;
            }
        }

        // Build <itemBody>
        $assessmentItem->setItemBody($this->itemBodyBuilder->buildItemBody($interactions, $questionType, $content));

        // Map <responseDeclaration>
        if (!empty($responseDeclarationCollection)) {
            $assessmentItem->setResponseDeclarations($responseDeclarationCollection);
        }

        // Map <responseProcessing> - combine response processing from questions
        if (count($questions) === 1) {
            // Single interaction items
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
        } else {
            // Composite items
            try {
                $responseIdentifiers = $identifiers;
                $fullScore = floatval($totalScore); // Total score if all responses are correct

                $responseProcessing = new ResponseProcessing();
                $equalExpressions = new ExpressionCollection();
                foreach ($responseIdentifiers as $identifier) {
                    $conditionExpressions = new ExpressionCollection();
                    $conditionExpressions->attach(new Variable($identifier));
                    $conditionExpressions->attach(new Correct($identifier));
                    $equal = new Equal($conditionExpressions);
                    $equalExpressions->attach($equal);
                }

                $andCondition = new AndOperator($equalExpressions);
                $setScore = new SetOutcomeValue("SCORE", new BaseValue(BaseType::FLOAT, $fullScore));
                $responseIfActions = new ResponseRuleCollection();
                $responseIfActions->attach($setScore);
                $responseIf = new ResponseIf($andCondition, $responseIfActions);
                $responseElseActions = new ResponseRuleCollection();
                $responseElseActions->attach(new SetOutcomeValue("SCORE", new BaseValue(BaseType::FLOAT, 0)));
                $responseElse = new ResponseElse($responseElseActions);
                $responseCondition = new ResponseCondition($responseIf, null, $responseElse);
                $responseProcessing->setResponseRules(new ResponseRuleCollection([$responseCondition]));
                $assessmentItem->setResponseProcessing($responseProcessing);
            } catch (\Exception $th) {
                var_dump($th->getMessage());
            }
        }

        return $assessmentItem;
    }

    private function map(Question $question, $i = null)
    {
        $type = $question->get_type();
        if (!in_array($type, Constants::$supportedQuestionTypes)) {
            throw new MappingException("Question type `$type` not yet supported to be mapped to QTI");
        }
        $clazz = new \ReflectionClass(self::MAPPER_CLASS_BASE . ucfirst($type . 'Mapper'));
        $questionTypeMapper = $clazz->newInstance();
        $questionReference = $question->get_reference();
        // We add a suffix to the identifier for composite items
        $interactionIdentifier = 'RESPONSE' . ($i ? "_$i" : '');
        $result = $questionTypeMapper->convert($question->get_data(), $interactionIdentifier, $questionReference);
        $result[] = $questionTypeMapper->getExtraContent();
        return $result;
    }

    public function buildFeature($itemIdentifier, $itemLabel, array $features, $content)
    {
        // Initialise our <assessmentItem>
        $assessmentItem = new AssessmentItem($itemIdentifier, $itemIdentifier, false);
        $assessmentItem->setLabel($itemLabel);
        $assessmentItem->setToolName('Learnosity');

        // Store interactions on this array to later be placed on <itemBody>
        $interactions = [];
        $responseDeclarationCollection = new ResponseDeclarationCollection();
        $responseProcessingTemplates = [];

        foreach ($features as $feature) {

            $featureData = $feature->to_array();
            $content = $featureData['content'];
            $featureType = $featureData['type'];
            $assessmentItem->setOutcomeDeclarations($this->buildScoreOutcomeDeclarations(0, 'SCORE'));

            // Map the `features` to be placed at <itemBody>
            list($interaction, $responseDeclaration) = $this->mapFeature($feature);
            if (!empty($responseDeclaration)) {
                $responseDeclarationCollection->attach($responseDeclaration);
            }

            $interactions[$feature->get_reference()]['interaction'] = $interaction;
        }

        // Build <itemBody>

        $assessmentItem->setItemBody($this->itemBodyBuilder->buildItemBody($interactions, $featureType, $content));
        // Map <responseDeclaration>
        if (!empty($responseDeclarationCollection)) {
            $assessmentItem->setResponseDeclarations($responseDeclarationCollection);
        }

        return $assessmentItem;
    }

    private function mapFeature(Feature $feature)
    {
        $type = $feature->get_type();
        if (!in_array($type, Constants::$supportedFeatureTypes)) {
            throw new MappingException("Feature type `$type` not yet supported to be mapped to QTI");
        }
        $clazz = new \ReflectionClass(self::MAPPER_CLASS_BASE . ucfirst($type . 'Mapper'));
        $featureTypeMapper = $clazz->newInstance();

        // Try to use feature `reference` as identifier
        // Otherwise, generate an alternative identifier and store the original reference as `label` to be passed in
        $featureReference = $feature->get_reference();

        $interactionIdentifier = 'RESPONSE';
        $result = $featureTypeMapper->convert($feature->get_data(), $interactionIdentifier, $featureReference);
        return $result;
    }

    private function buildScoreOutcomeDeclarations($score, $type)
    {
        // Set <outcomeDeclaration> with assumption default value is always 0
        $outcomeDeclaration = new OutcomeDeclaration($type, BaseType::FLOAT);
        $valueCollection = new ValueCollection();
        $valueCollection->attach(new Value($score));
        $outcomeDeclaration->setDefaultValue(new DefaultValue($valueCollection));

        $outcomeDeclarationCollection = $this->outcomeDeclarationCollection;
        $outcomeDeclarationCollection->attach($outcomeDeclaration);
        return $outcomeDeclarationCollection;
    }

    private function buildFeedbackOutcomeDeclarations($identifire, $cardinality = Cardinality::SINGLE, $baseType = BaseType::IDENTIFIER)
    {
        // Set <outcomeDeclaration> with  FEEDBACK identifier
        $outcomeDeclaration = new OutcomeDeclaration($identifire, $baseType, $cardinality);
        $outcomeDeclarationCollection = $this->outcomeDeclarationCollection;
        $outcomeDeclarationCollection->attach($outcomeDeclaration);
        return $outcomeDeclarationCollection;
    }

    private function buildModalFeedBack($feedBackText, $identifier, $outComeidentifier)
    {
        $content = new FlowStaticCollection(array(new TextRun($feedBackText)));
        $modalFeedback = new ModalFeedback($identifier, $outComeidentifier, $content);
        return $modalFeedback;
    }

    private function buildMediaInteraction($questionData)
    {
        $src = $questionData['features'][0]['data']['src'];
        $object = new ObjectElement($src, MimeUtil::guessMimeType($src));
        // Build final interaction and its corresponding <responseDeclaration>, and its <responseProcessingTemplate>
        $mediaInteraction = new MediaInteraction('MEDIA_RESPONSE', true, $object);
        $mediaInteraction->setAutostart(true);
        $mediaInteraction->setMinPlays(1);
        $mediaInteraction->setMaxPlays(5);
        $mediaInteraction->setLabel($questionData['features'][0]['reference']);
        // Set loop
        $mediaInteraction->setLoop(false);
        $builder = new featureValidationBuilder();
        list($responseDeclaration) = $builder->buildValidation('MEDIA_RESPONSE', '', []);

        return [$mediaInteraction, $responseDeclaration];
    }

    private function getBaseType($questionType)
    {
        switch ($questionType) {
            case 'clozeassociation':
                return BaseType::DIRECTED_PAIR;
                break;
            default:
                return BaseType::IDENTIFIER;
        }
    }
}
