<?php

namespace Learnosity\Mappers\QtiV2\Import\Validation;

class GraphicGapMatchInteractionValidationBuilder extends BaseGapMatchInteractionValidationBuilder
{
    function getValidationClassName()
    {
        return 'imageclozeassociation';
    }
}