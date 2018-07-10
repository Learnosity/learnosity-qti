<?php

namespace LearnosityQti\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Entities\QuestionTypes\hotspot;
use LearnosityQti\Entities\QuestionTypes\hotspot_image;
use LearnosityQti\Processors\QtiV2\Out\Validation\HotspotValidationBuilder;
use LearnosityQti\Utils\CurlUtil;
use LearnosityQti\Utils\MimeUtil;
use qtism\common\datatypes\QtiCoords;
use qtism\common\datatypes\QtiShape;
use qtism\data\content\interactions\HotspotChoice;
use qtism\data\content\interactions\HotspotChoiceCollection;
use qtism\data\content\interactions\HotspotInteraction;
use qtism\data\content\xhtml\ObjectElement;

class HotspotMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var hotspot $question */
        $question = $questionType;

        // Get main image width and height
        $width = $question->get_image()->get_width();
        $height = $question->get_image()->get_height();

        // Build <hotspotInteraction>
        $valueIdentifierMap = [];
        $choicesCollection = new HotspotChoiceCollection();
        foreach ($question->get_areas() as $index => $area) {
            $coords = [];
            foreach ($area as $coord) {
                $coords[] = intval(($coord['x'] / 100) * $width);
                $coords[] = intval(($coord['y'] / 100) * $height);
            }
            $choiceIdentifier = 'CHOICE_' . $index;
            $valueIdentifierMap[$index] = $choiceIdentifier;
            $hotspotChoice = new HotspotChoice($choiceIdentifier, QtiShape::POLY, new QtiCoords(QtiShape::POLY,  $coords));
            $choicesCollection->attach($hotspotChoice);
        }

        // Build final interaction and its corresponding <responseDeclaration>, and its <responseProcessingTemplate>
        $imageObject = $this->buildMainImageObject($question->get_image());
        $hasMultiResponses = !empty($question->get_multiple_responses()) && $question->get_multiple_responses();
        $maxChoices = $hasMultiResponses ? $choicesCollection->count() : 1;
        $interaction = new HotspotInteraction($interactionIdentifier, $imageObject, $choicesCollection);
        $interaction->setMaxChoices($maxChoices);

        // Set min choices are 0 because we allow no responses
        $interaction->setMinChoices(0);

        // Build the prompt
        $interaction->setPrompt($this->convertStimulusForPrompt($question->get_stimulus()));
        
        if (empty($question->get_validation())) {
            return [$interaction, null, null];
        }

        $builder = new HotspotValidationBuilder($question->get_multiple_responses(), $valueIdentifierMap);
        list($responseDeclaration, $responseProcessing) = $builder->buildValidation($interactionIdentifier, $question->get_validation());

        return [$interaction, $responseDeclaration, $responseProcessing];
    }

    private function buildMainImageObject(hotspot_image $image)
    {
        $imageSrc = $image->get_source();
        $imageObject = new Object($imageSrc, MimeUtil::guessMimeType($imageSrc));

        $imageWidth = null;
        $imageHeight = null;
        if (!empty($image->get_width()) && !empty($image->get_height())) {
            $imageWidth = $image->get_width();
            $imageHeight = $image->get_height();
        } else {
            list($imageWidth, $imageHeight) = CurlUtil::getImageSize(CurlUtil::prepareUrlForCurl($imageSrc));
        }
        $imageObject->setWidth($imageWidth);
        $imageObject->setHeight($imageHeight);
        return $imageObject;
    }
}
