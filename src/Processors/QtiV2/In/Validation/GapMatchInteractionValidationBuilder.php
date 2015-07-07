<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

class GapMatchInteractionValidationBuilder extends BaseGapMatchInteractionValidationBuilder
{
    public function getValidationClassName()
    {
        return 'clozeassociation';
    }
}
