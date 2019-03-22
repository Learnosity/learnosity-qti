<?php

namespace LearnosityQti\Processors\QtiV2\In\Interactions;

use LearnosityQti\Entities\QuestionTypes\imageclozeassociation;
use LearnosityQti\Entities\QuestionTypes\imageclozeassociation_image;
use LearnosityQti\Entities\QuestionTypes\imageclozedropdown_response_containers_item;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\In\Validation\GapMatchInteractionValidationBuilder;
use LearnosityQti\Utils\QtiCoordinateUtil;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\AssociableHotspot;
use qtism\data\content\interactions\GapChoice;
use qtism\data\content\interactions\GraphicGapMatchInteraction as QtiGraphicGapMatchInteraction;
use qtism\data\content\xhtml\ObjectElement;

class GraphicGapMatchInteractionMapper extends AbstractInteractionMapper
{
    public function getQuestionType()
    {
        /** @var QtiGraphicGapMatchInteraction $interaction */
        $interaction = $this->interaction;
        /** @var Object $imageObject */
        $imageObject = $interaction->getObject();

        // Yes, width and height is necessary unfortunately
        if ($imageObject->getHeight() < 0 || $imageObject->getWidth() < 0) {
            throw new MappingException('Hotspot interaction image object need to specifiy both width and height for conversion');
        }

        $possibleResponseMapping = $this->buildPossibleResponseMapping($interaction);
        $associableHotspots = $this->buildTemplate($interaction, $imageObject);

        $image = new imageclozeassociation_image();
        $image->set_src($imageObject->getData());

        $responseContainers = [];
        $responsePosition = [];
        foreach ($associableHotspots as $associableHotspot) {
            $responsePosition [] = [
                'x' => $associableHotspot['x'],
                'y' => $associableHotspot['y']
            ];
            $responseContainer = new imageclozedropdown_response_containers_item();
            $responseContainer->set_height($associableHotspot['height'] . 'px');
            $responseContainer->set_width($associableHotspot['width'] . 'px');
            $responseContainers[] = $responseContainer;
        }

        $question = new imageclozeassociation(
            $image,
            $responsePosition,
            'imageclozeassociation',
            array_values($possibleResponseMapping)
        );
        $question->set_response_containers($responseContainers);
        $question->set_stimulus($this->getPrompt());

        // Build dah` validation
        $hotspotIdentifiers = array_keys($associableHotspots);
        $validationBuilder = new GapMatchInteractionValidationBuilder(
            'imageclozeassociation',
            $hotspotIdentifiers,
            $possibleResponseMapping,
            $this->responseDeclaration
        );
        $validation = $validationBuilder->buildValidation($this->responseProcessingTemplate);
        if ($validation) {
            $question->set_validation($validation);
        }
        $question->set_duplicate_responses($validationBuilder->isDuplicatedResponse());
        return $question;
    }

    protected function buildPossibleResponseMapping(QtiGraphicGapMatchInteraction $interaction)
    {
        $possibleResponseMapping = [];
        $gapChoices = $interaction->getGapImgs();
        /** @var GapChoice $gapChoice */
        foreach ($gapChoices as $gapChoice) {
            $gapChoiceContent = QtiMarshallerUtil::marshallCollection($gapChoice->getComponents());
            $possibleResponseMapping[$gapChoice->getIdentifier()] = $gapChoiceContent;
        }
        return $possibleResponseMapping;
    }

    protected function buildTemplate(QtiGraphicGapMatchInteraction $interaction, Object $object)
    {
        $associableHotspots = [];
        foreach ($interaction->getAssociableHotspots() as $associableHotspot) {
            /** @var AssociableHotspot $associableHotspot */
            $associableHotspots[$associableHotspot->getIdentifier()] =
                QtiCoordinateUtil::convertQtiCoordsToPercentage(
                    [$object->getWidth(), $object->getHeight()],
                    explode(',', $associableHotspot->getCoords()),
                    $associableHotspot->getShape()
                );
        }
        return $associableHotspots;
    }
}
