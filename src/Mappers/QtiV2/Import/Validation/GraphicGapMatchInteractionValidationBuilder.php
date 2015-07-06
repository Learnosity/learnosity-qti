<?php

namespace Learnosity\Mappers\QtiV2\Import\Validation;

class GraphicGapMatchInteractionValidationBuilder extends BaseGapMatchInteractionValidationBuilder
{
    public function getValidationClassName()
    {
        return 'imageclozeassociation';
    }
}
