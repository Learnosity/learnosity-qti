<?php

namespace LearnosityQti\Processors\QtiV2\In\Interactions;

use LearnosityQti\Entities\QuestionTypes\imageclozeassociationV2;
use LearnosityQti\Entities\QuestionTypes\imageclozeassociationV2_image;
use LearnosityQti\Entities\QuestionTypes\imageclozeassociationV2_response_containers_item;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\In\Constants;
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
        /** @var ObjectElement $imageObject */
        $imageObject = $interaction->getObject();

        // Yes, width and height is necessary unfortunately
        if ($imageObject->getHeight() < 0 || $imageObject->getWidth() < 0) {
            throw new MappingException('Hotspot interaction image object need to specifiy both width and height for conversion');
        }

        $possibleResponseMapping = $this->buildPossibleResponseMapping($interaction);
        $associableHotspots = $this->buildTemplate($interaction, $imageObject);

        $image = new imageclozeassociationV2_image();
        // Make sure to have the Learnosity base asset URL, with the organisation_id, then the filename.
        // Nothing else should be in the path
        $imageArray = explode('/',$imageObject->getData());
        $imageBaseUrl = Constants::$baseLearnosityAssetsUrl . $this->organisationId . '/' . end($imageArray);
        $image->set_src($imageBaseUrl);
        $image->set_width($imageObject->getWidth());
        $image->set_height($imageObject->getHeight());

        $responseContainers = [];
        foreach ($associableHotspots as $associableHotspot) {
            $responseContainer = new imageclozeassociationV2_response_containers_item();
            $responseContainer->set_height($associableHotspot['height'] . 'px');
            $responseContainer->set_width($associableHotspot['width'] . 'px');
            $responseContainer->set_x($associableHotspot['x']);
            $responseContainer->set_y($associableHotspot['y']);
            $responseContainer->set_aria_label('');
            $responseContainers[] = $responseContainer;
        }

        $question = new imageclozeassociationV2(
            'imageclozeassociationV2',
            $image,
            array_values($possibleResponseMapping)
        );
        $question->set_response_containers($responseContainers);
        $question->set_stimulus($this->getPrompt());

        // Build dah` validation
        $hotspotIdentifiers = array_keys($associableHotspots);
        $validationBuilder = new GapMatchInteractionValidationBuilder(
            'imageclozeassociationV2',
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

    protected function buildTemplate(QtiGraphicGapMatchInteraction $interaction, ObjectElement $object)
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
