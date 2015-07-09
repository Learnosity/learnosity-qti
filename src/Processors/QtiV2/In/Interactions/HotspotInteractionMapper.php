<?php

namespace Learnosity\Processors\QtiV2\In\Interactions;

use Learnosity\Entities\QuestionTypes\imageclozeassociation;
use Learnosity\Entities\QuestionTypes\imageclozeassociation_image;
use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use Learnosity\Processors\QtiV2\In\Validation\HotspotInteractionValidationBuilder;
use qtism\data\content\interactions\HotspotInteraction;

class HotspotInteractionMapper extends AbstractInteractionMapper
{
    public function getQuestionType()
    {
        /** @var HotspotInteraction $interaction */
        $interaction = $this->interaction;

        /** @var Object $interactionImage */
        $interactionImage = $interaction->getObject();

        $image = new imageclozeassociation_image();
        $image->set_src($interactionImage->getData());

        $responsePositionsMapping = QtiComponentUtil::convertHotspotChoiceCollectionToResponsePositionMapping(
            $interactionImage->getWidth(),
            $interactionImage->getHeight(),
            $interaction->getHotspotChoices()
        );

        // Build `dah actual question
        $marker = 'X';
        $question = new imageclozeassociation(
            $image,
            $responsePositionsMapping,
            'imageclozeassociation',
            [$marker]
        );
        $question->set_stimulus($this->getPrompt());

        // Build `dah validation
        $responsePositionIndexMap = array_flip(array_keys($responsePositionsMapping));
        $validationBuilder = new HotspotInteractionValidationBuilder(
            $marker,
            $responsePositionIndexMap,
            $this->responseDeclaration
        );
        $validation = $validationBuilder->buildValidation($this->responseProcessingTemplate);
        if ($validation) {
            $question->set_validation($validation);
        }
        $question->set_validation($validation);

        return $question;
    }
}
