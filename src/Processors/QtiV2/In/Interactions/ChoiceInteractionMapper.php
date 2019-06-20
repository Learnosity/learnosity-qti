<?php
namespace LearnosityQti\Processors\QtiV2\In\Interactions;

use LearnosityQti\Entities\QuestionTypes\mcq;
use LearnosityQti\Entities\QuestionTypes\mcq_metadata;
use LearnosityQti\Entities\QuestionTypes\mcq_ui_style;
use LearnosityQti\Services\ConvertToLearnosityService;
use LearnosityQti\Utils\HtmlExtractorUtil;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Processors\QtiV2\In\Validation\ChoiceInteractionValidationBuilder;
use LearnosityQti\Services\LogService;
use qtism\data\content\FeedbackInline;
use qtism\data\content\interactions\Orientation;
use qtism\data\content\interactions\Prompt;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\content\interactions\SimpleChoiceCollection;

class ChoiceInteractionMapper extends AbstractInteractionMapper
{

    public function getQuestionType()
    {
        /* @var QtiChoiceInteraction $interaction */
        $interaction = $this->interaction;

        $options = $this->buildOptions($interaction->getSimpleChoices());
        $feedbackMetadata = $this->buildFeedbackMetadata($interaction->getSimpleChoices());
        $mcq = new mcq('mcq', $options);

        // Support for @mcq-metadata
        foreach ($feedbackMetadata as $value) {
            if (!empty($value)) {
                $metaData = new mcq_metadata();
                $metaData->set_distractor_rationale_response_level($feedbackMetadata);
                $mcq->set_metadata($metaData);
            }
        }

        // Support for @shuffle
        $mustShuffle = $interaction->mustShuffle();
        if ($mustShuffle) {
            $mcq->set_shuffle_options($mustShuffle);
            LogService::log('Set shuffle choices as true, however `fixed` attribute would be ignored since we don\'t support partial shuffle');
        }

        // Support for @orientation ('vertical' or 'horizontal')
        $uiStyle = new mcq_ui_style();
        if ($interaction->getOrientation() === Orientation::HORIZONTAL) {
            $uiStyle->set_type('horizontal');
            $uiStyle->set_columns(count($options));
            $mcq->set_ui_style($uiStyle);
        }

        // Support mapping for <prompt>
        if ($interaction->getPrompt() instanceof Prompt) {
            $promptContent = $interaction->getPrompt()->getContent();
            $mcq->set_stimulus(QtiMarshallerUtil::marshallCollection($promptContent));
        }

        // Partial support for @maxChoices
        // @maxChoices of 0 or more than 1 would then map to choicematrix
        $maxChoices = $interaction->getMaxChoices();
        if ($maxChoices !== 1) {
            if ($maxChoices !== 0 && $maxChoices !== count($options)) {
                // We do not support specifying amount of choices
                LogService::log(
                    "Allowing multiple responses of max " . count($options) . " options, however " .
                    "maxChoices of $maxChoices would be ignored since we can't support exact number"
                );
            }
            $mcq->set_multiple_responses(true);
        }

        // Ignoring @minChoices
        if (!empty($interaction->getMinChoices())) {
            LogService::log('Attribute minChoices is not supported. Thus, ignored');
        }

        // Build validation
        $validationBuilder = new ChoiceInteractionValidationBuilder(
            $this->responseDeclaration,
            array_column($options, 'label', 'value'),
            $maxChoices,
            $this->outcomeDeclarations
        );
        $validation = $validationBuilder->buildValidation($this->responseProcessingTemplate);
        if (!empty($validation)) {
            $mcq->set_validation($validation);
        }
        return $mcq;
    }

    private function buildOptions(SimpleChoiceCollection $simpleChoices)
    {
        /* @var $choice SimpleChoice */
        $options = [];
        foreach ($simpleChoices as $key => $choice) {
            // Store 'SimpleChoice' identifier to key for validation purposes
            $options[] = [
                'label' => trim(QtiMarshallerUtil::marshallCollection($choice->getContent())),
                'value' => $choice->getIdentifier()
            ];
        }
        return $options;
    }

    /**
     * This function is used to create distractor_rationale_response_level from feedbackInline
     *
     * @param SimpleChoiceCollection $simpleChoices
     * @return string
     */
    private function buildFeedbackMetadata(SimpleChoiceCollection $simpleChoices)
    {
        /* @var $choice SimpleChoice */
        $metadata = [];
        foreach ($simpleChoices as $choice) {
            $flow = $choice->getContent();
            if (property_exists($flow, 'dataPlaceHolder')) {
                $class = new \ReflectionClass(get_class($flow));
                $property = $class->getProperty('dataPlaceHolder');
                $property->setAccessible(true);
                $feed = $property->getValue($flow);
                $count = 0;
                foreach ($feed as $feeddata) {
                    if ($feeddata instanceof FeedbackInline) {
                        $count++;
                        $metadata[] = $this->buildMetadataForFeedbackInline($feeddata);
                    }
                }
                if ($count == 0) {
                    $metadata[] = "";
                }
            }
        }

        return $metadata;
    }

    /**
     * This function is used to create feedbackInline data
     *
     * @param FeedbackInline $feeddata
     * @return string
     */
    protected function buildMetadataForFeedbackInline(FeedbackInline $feeddata)
    {
        $metadata = "";
        $feeddataArray = array_values((array) $feeddata);
        $feedbackArray = array_values((array) $feeddataArray[3]);
        if (sizeof($feedbackArray[0]) >= 2) {
            $feedInlineArray = array_values((array) $feedbackArray[0][1]);
            if (!empty($feedInlineArray) && $feedInlineArray[2] == 'text/html') {
                $learnosityServiceObject = ConvertToLearnosityService::getInstance();
                $inputPath = $learnosityServiceObject->getInputpath();
                $htmlfile = $inputPath . '/' . $feedInlineArray[1];
                $metadata = HtmlExtractorUtil::getHtmlData($htmlfile);
            }
        } else {
            $feeddataArray = array_values((array) $feedbackArray[0][0]);
            if (!empty($feeddataArray[0])) {
                $metadata = trim($feeddataArray[0]);
            }
        }

        return $metadata;
    }
}
