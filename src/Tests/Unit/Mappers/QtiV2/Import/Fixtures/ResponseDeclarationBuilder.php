<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures;

use qtism\data\state\CorrectResponse;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;
use qtism\data\state\ValueCollection;

class ResponseDeclarationBuilder
{
    public static function buildWithCorrectResponse($identifier, array $values)
    {
        $responseDeclaration = new ResponseDeclaration($identifier);
        $valueCollection = new ValueCollection();
        foreach ($values as $value) {
            $valueCollection->attach(new Value($value));
        }
        $responseDeclaration->setCorrectResponse(new CorrectResponse($valueCollection));
        return $responseDeclaration;
    }
} 
