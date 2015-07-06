<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;

use Learnosity\Entities\QuestionTypes\imageclozeassociation;
use Learnosity\Entities\QuestionTypes\imageclozeassociation_image;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use Learnosity\Mappers\QtiV2\Import\Validation\GraphicGapMatchInteractionValidationBuilder;
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

        $validationBuilder = new GraphicGapMatchInteractionValidationBuilder(
            array_keys($associableHotspots),
            $possibleResponseMapping,
            $this->responseDeclaration,
            $this->responseProcessingTemplate
        );

        $validation = $validationBuilder->getValidation();

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

        if ($validation) {
            $question->set_validation($validation);
        }

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
