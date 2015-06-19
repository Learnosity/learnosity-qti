<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;

use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use qtism\data\content\interactions\Interaction;
use qtism\data\state\ResponseDeclaration;

abstract class AbstractInteraction
{
    protected $interaction;
    protected $responseDeclaration;
    protected $responseProcessingTemplate;
    protected $exceptions;

    public function __construct(Interaction $interaction, ResponseDeclaration $responseDeclaration = null, ResponseProcessingTemplate $responseProcessingTemplate = null)
    {
        $this->interaction = $interaction;
        $this->responseDeclaration = $responseDeclaration;
        $this->responseProcessingTemplate = $responseProcessingTemplate;
    }

    // TODO: Need to verify for <math> tags to see whether we need to enable 'is_math'
    abstract public function getQuestionType();

    public function getExceptions()
    {
        return $this->exceptions;
    }

    protected function mutateResponses(array $responses)
    {
        if (count($responses) <= 1) {
            return array_values($responses[0]);
        } else {
            $res = [];
            $first = array_shift($responses);
            $remaining = $this->mutateResponses($responses);
            foreach ($first as $fKey => $f) {
                foreach ($remaining as $rKey => $r) {
                    if (!is_array($f)) {
                        $f = [$f];
                    }
                    if (!is_array($r)) {
                        $r = [$r];
                    }
                    $res[] = array_merge($f, $r);
                }
            }
            return $res;
        }

    }
}
