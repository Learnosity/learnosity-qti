<?php

namespace Learnosity\Mappers\QtiV2\Import\MergedInteractions;

use Learnosity\Entities\QuestionTypes\clozetext;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use qtism\data\content\interactions\Interaction;
use qtism\data\content\ItemBody;

class MergedTextEntryInteraction extends AbstractMergedInteraction
{
    public function getQuestionType()
    {
        $interactionComponents = $this->itemBody->getComponentsByClassName('textEntryInteraction', true);

        $interactionXmls = [];
        /** @var Interaction $component */
        foreach ($interactionComponents as $component) {
            $interactionXmls[] = QtiComponentUtil::marshall($component);
        }

        //TODO: Throw all the warnings to an array
        $closetext = new clozetext('clozetext', $this->buildTemplate($this->itemBody, $interactionXmls));
        return $closetext;
    }

    private function buildTemplate(ItemBody $itemBody, array $interactionXmls)
    {
        // Build item's HTML content
        $content = QtiComponentUtil::marshallCollection($itemBody->getComponents());
        foreach ($interactionXmls as $interactionXml) {
            $content = str_replace($interactionXml, '{{response}}', $content);
        }
        return $content;
    }

    public function getItemContent()
    {
        return '<span class="learnosity-response question-' . $this->questionReference . '"></span>';
    }
}
