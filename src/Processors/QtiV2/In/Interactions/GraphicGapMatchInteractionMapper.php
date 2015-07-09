<?php

namespace Learnosity\Processors\QtiV2\In\Interactions;

use Learnosity\Entities\QuestionTypes\imageclozeassociation;
use Learnosity\Entities\QuestionTypes\imageclozeassociation_image;
use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use Learnosity\Processors\QtiV2\In\Validation\GapMatchInteractionValidationBuilder;
use qtism\data\content\interactions\AssociableHotspot;
use qtism\data\content\interactions\GapChoice;
use qtism\data\content\interactions\GraphicGapMatchInteraction as QtiGraphicGapMatchInteraction;
use qtism\data\content\xhtml\Object;

class GraphicGapMatchInteractionMapper extends AbstractInteractionMapper
{
    public function getQuestionType()
    {
        /** @var QtiGraphicGapMatchInteraction $interaction */
        $interaction = $this->interaction;
        /** @var Object $imageObject */
        $imageObject = $interaction->getObject();
        $possibleResponseMapping = $this->buildPossibleResponseMapping($interaction);
        $associableHotspots = $this->buildTemplate($interaction, $imageObject);

        $image = new imageclozeassociation_image();
        $image->set_src($imageObject->getData());

        $responsePosition = [];
        foreach ($associableHotspots as $associableHotspot) {
            $responsePosition [] = [
                'x' => $associableHotspot['x'],
                'y' => $associableHotspot['y']
            ];
        }

        $question = new imageclozeassociation(
            $image,
            $responsePosition,
            'imageclozeassociation',
            array_values($possibleResponseMapping)
        );

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
        $this->exceptions = array_merge($this->exceptions, $validationBuilder->getExceptions());

        return $question;
    }

    protected function buildPossibleResponseMapping(QtiGraphicGapMatchInteraction $interaction)
    {
        $possibleResponseMapping = [];
        $gapChoices = $interaction->getGapImgs();
        /** @var GapChoice $gapChoice */
        foreach ($gapChoices as $gapChoice) {
            $gapChoiceContent = QtiComponentUtil::marshallCollection($gapChoice->getComponents());
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
                QtiComponentUtil::convertQtiCoordsToPercentage(
                    [
                        $object->getWidth(),
                        $object->getHeight()
                    ],
                    explode(',', $associableHotspot->getCoords()),
                    $associableHotspot->getShape()
                );
        }
        return $associableHotspots;
    }
}
