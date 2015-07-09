<?php

namespace Learnosity\Processors\QtiV2\In\Interactions;

use Learnosity\Entities\QuestionTypes\imageclozeassociation;
use Learnosity\Entities\QuestionTypes\imageclozeassociation_image;
use Learnosity\Entities\QuestionTypes\imageclozeassociation_ui_style;
use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use Learnosity\Processors\QtiV2\In\Validation\GraphicOrderInteractionValidationBuilder;
use qtism\data\content\interactions\GraphicOrderInteraction;
use qtism\data\content\interactions\HotspotChoice;
use qtism\data\content\interactions\HotspotChoiceCollection;

class GraphicOrderInteractionMapper extends AbstractInteractionMapper
{
    public function getQuestionType()
    {
        /** @var GraphicOrderInteraction $interaction */
        $interaction = $this->interaction;

        /** @var Object $interactionImage */
        $interactionImage = $interaction->getObject();

        // Build `response_positions`
        $responsePositionsMapping = QtiComponentUtil::convertHotspotChoiceCollectionToResponsePositionMapping(
            $interactionImage->getWidth(),
            $interactionImage->getHeight(),
            $interaction->getHotspotChoices()
        );

        // Build `possible_responses`, in this case we assume a simple array of string
        // ie. `1`, `2`,... matching response count
        $possibleResponses = range(1, count($responsePositionsMapping));

        // Build `dah actual question
        $image = new imageclozeassociation_image();
        $image->set_src($interactionImage->getData());
        $question = new imageclozeassociation(
            $image,
            array_values($responsePositionsMapping),
            'imageclozeassociation',
            $possibleResponses
        );
        $question->set_stimulus($this->getPrompt());

        // Build `validation` object
        $responsePositionIndexMap = array_flip(array_keys($responsePositionsMapping));
        $validationBuilder = new GraphicOrderInteractionValidationBuilder($responsePositionIndexMap, $this->responseDeclaration);
        $validation = $validationBuilder->buildValidation($this->responseProcessingTemplate);
        if ($validation) {
            $question->set_validation($validation);
        }
        $this->exceptions = array_merge($this->exceptions, $validationBuilder->getExceptions());
        return $question;
    }

    protected function buildResponsePositionsMapping($imageWidth, $imageHeight, HotspotChoiceCollection $hotspotChoices)
    {
        $responsePositionsMapping = [];
        /** @var HotspotChoice $hotspotChoice */
        foreach ($hotspotChoices as $hotspotChoice) {
            $percentage = QtiComponentUtil::convertQtiCoordsToPercentage(
                [$imageWidth, $imageHeight],
                explode(',', $hotspotChoice->getCoords()),
                $hotspotChoice->getShape()
            );
            $responsePositionsMapping[$hotspotChoice->getIdentifier()] = $percentage;
        }
        return $responsePositionsMapping;
    }
}
