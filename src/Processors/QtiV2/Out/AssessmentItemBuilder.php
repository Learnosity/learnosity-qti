<?php

namespace LearnosityQti\Processors\QtiV2\Out;

use LearnosityQti\Entities\Question;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Utils\StringUtil;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\common\utils\Format;
use qtism\data\AssessmentItem;
use qtism\data\content\FlowStaticCollection;
use qtism\data\content\ModalFeedback;
use qtism\data\content\ModalFeedbackCollection;
use qtism\data\content\TextRun;
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
        //$assessmentItem->setOutcomeDeclarations($this->buildOutcomeDeclarations());
        $assessmentItem->setToolName('Learnosity');
        
        // Store interactions on this array to later be placed on <itemBody>
        $interactions = [];
        $responseDeclarationCollection = new ResponseDeclarationCollection();
        $responseProcessingTemplates = [];
        
        foreach ($questions as $question) {
            
            $questionData = $question->to_array();
            $content = $questionData['content'];
            $questionType = $questionData['type']; 
            $assessmentItem->setOutcomeDeclarations($this->buildScoreOutcomeDeclarations(0, 'SCORE'));
            
            // add outcome declaration for MAXSCORE
            if(isset($questionData['data']['validation']['max_score'])){  
                $max_score = $questionData['data']['validation']['max_score']; 
                $assessmentItem->setOutcomeDeclarations($this->buildScoreOutcomeDeclarations($max_score, 'MAXSCORE'));
            }
            
            // add outcome declaration for MINSCORE
            if(isset($questionData['data']['validation']['min_score_if_attempted'])){  
                $min_score = $questionData['data']['validation']['min_score_if_attempted']; 
                $assessmentItem->setOutcomeDeclarations($this->buildScoreOutcomeDeclarations($min_score, 'MINSCORE'));
            }
            
            $baseType = $this->getBaseType($questionData['type']);
            
            // add outcome declaration for FEEDBACK INLINE
            if(isset($questionData['data']['metadata']['distractor_rationale_response_level'])){
               $assessmentItem->setOutcomeDeclarations($this->buildFeedbackOutcomeDeclarations('FEEDBACK', Cardinality::MULTIPLE, $baseType));
            }
            
            if(isset($questionData['data']['metadata']['distractor_rationale'])){
                $distractorRational = $questionData['data']['metadata']['distractor_rationale'];
                $assessmentItem->setOutcomeDeclarations($this->buildFeedbackOutcomeDeclarations('FEEDBACK_GENERAL'));
                $assessmentItem->setModalFeedbacks(new ModalFeedbackCollection(array($this->buildModalFeedBack($distractorRational, 'FEEDBACK_GENERAL', 'correctOrIncorrect'))));
            }
            
            /** @var Question $question */
            // Map the `questions` and its validation objects to be placed at <itemBody>
            // The extraContent usually comes from `stimulus` of item that mapped to inline interaction and has no `prompt`
            list($interaction, $responseDeclaration, $responseProcessing, $extraContent) = $this->map($question);
            if (!empty($responseDeclaration)){
                
                if($responseDeclaration instanceof ResponseDeclarationCollection && $responseDeclaration->count()>0){
                    for($i=1;$i<=sizeof($responseDeclaration);$i++){
                        $assessmentItem->setOutcomeDeclarations($this->buildScoreOutcomeDeclarations(0.0, 'SCORE'.$i));
                    }
                    $responseDeclarationCollection->merge($responseDeclaration);
                }
                else {
                    $responseDeclarationCollection->attach($responseDeclaration);
                }
            }
            
            if (!empty($responseProcessing)){
                /** @var ResponseProcessing $responseProcessing */
                $responseProcessingTemplates[] = $responseProcessing->getTemplate();
            }
            
            $interactions[$question->get_reference()]['interaction'] = $interaction;
            if (!empty($extraContent)) {
                $interactions[$question->get_reference()]['extraContent'] = $extraContent;
            }
        }
        
        // Build <itemBody>
        
        $assessmentItem->setItemBody($this->itemBodyBuilder->buildItemBody($interactions, $content, $questionType));
        // Map <responseDeclaration>
        if (!empty($responseDeclarationCollection)) {
            $assessmentItem->setResponseDeclarations($responseDeclarationCollection);
        }
        // Map <responseProcessing> - combine response processing from questions
        // TODO: Tidy up this stuff
        if (!empty($responseProcessingTemplates)) {
            if(!empty($responseProcessingTemplates[0])){
                $templates = array_unique($responseProcessingTemplates);
                $isOnlyMatchCorrect = count($templates) === 1 && $templates[0] === Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT;
                $responseProcessing = new ResponseProcessing();
                $responseProcessing->setTemplate($isOnlyMatchCorrect ? Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT : Constants::RESPONSE_PROCESSING_TEMPLATE_MAP_RESPONSE);
                $assessmentItem->setResponseProcessing($responseProcessing);
            }else{
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
        
        // Try to use question `reference` as identifier
        // Otherwise, generate an alternative identifier and store the original reference as `label` to be passed in
        $questionReference = $question->get_reference();
        $interactionIdentifier = Format::isIdentifier($questionReference, false) ? $questionReference : strtoupper($type)  . '_' . StringUtil::generateRandomString(12);
        
        /* if ($interactionIdentifier !== $questionReference) {
            LogService::log(
                "The question `reference` ($questionReference) is not a valid identifier. " .
                "Replaced it with randomly generated `$interactionIdentifier` and stored the original `reference` as `label` attribute"
            );
        } */
        $interactionIdentifier = 'RESPONSE';
        $result = $questionTypeMapper->convert($question->get_data(), $interactionIdentifier, $questionReference);
        $result[] = $questionTypeMapper->getExtraContent();
        return $result;
    }
    
    private function buildScoreOutcomeDeclarations($score, $type)
    {
        // Set <outcomeDeclaration> with assumption default value is always 0
        $outcomeDeclaration = new OutcomeDeclaration($type, BaseType::FLOAT);
        $valueCollection = new ValueCollection();
        $valueCollection->attach(new Value($score));
        $outcomeDeclaration->setDefaultValue(new DefaultValue($valueCollection));
        //$outcomeDeclarationCollection = new OutcomeDeclarationCollection();
        $outcomeDeclarationCollection = $this->outcomeDeclarationCollection;
        $outcomeDeclarationCollection->attach($outcomeDeclaration);
        return $outcomeDeclarationCollection;
    }
    
    private function buildFeedbackOutcomeDeclarations($identifire, $cardinality = Cardinality::SINGLE, $baseType = BaseType::IDENTIFIER)
    {
        // Set <outcomeDeclaration> with FEEDBACK identifier 
        $outcomeDeclaration = new OutcomeDeclaration($identifire, $baseType, $cardinality);
        $outcomeDeclarationCollection = $this->outcomeDeclarationCollection;
        $outcomeDeclarationCollection->attach($outcomeDeclaration);
        return $outcomeDeclarationCollection;
    }
    
    private function buildModalFeedBack($feedBackText, $identifier, $outComeidentifier){
        $content = new FlowStaticCollection(array(new TextRun($feedBackText)));
        $modalFeedback = new ModalFeedback($identifier, $outComeidentifier, $content);
        return $modalFeedback;
    }
    
    private function getBaseType($questionType) {
        switch ($questionType) {
            case 'clozeassociation':
                return BaseType::DIRECTED_PAIR;
                break;
            default:
                return BaseType::IDENTIFIER;
        }
    }

}