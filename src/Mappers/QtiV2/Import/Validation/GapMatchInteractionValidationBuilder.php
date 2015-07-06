<?php

namespace Learnosity\Mappers\QtiV2\Import\Validation;

class GapMatchInteractionValidationBuilder extends BaseGapMatchInteractionValidationBuilder
{
    public function getValidationClassName()
    {
        return 'clozeassociation';
    }
}
