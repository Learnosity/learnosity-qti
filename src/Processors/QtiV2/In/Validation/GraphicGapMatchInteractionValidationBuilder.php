<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

class GraphicGapMatchInteractionValidationBuilder extends BaseGapMatchInteractionValidationBuilder
{
    public function getValidationClassName()
    {
        return 'imageclozeassociation';
    }
}
