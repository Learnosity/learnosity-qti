<?php

namespace Learnosity\Mappers\QtiV2\Import\Marshallers;

use qtism\data\QtiComponent;
use qtism\data\storage\xml\marshalling\ObjectMarshaller;

class LearnosityObjectMarshaller extends ObjectMarshaller
{
    const MIME_IMAGE = 'image';
    const MIME_AUDIO = 'audio';
    const MIME_HTML = 'html';
    const MIME_NOT_SUPPORTED = 'na';

    protected function marshallChildrenKnown(QtiComponent $component, array $elements)
    {
        switch ($this->getMIMEType($component->getType())) {
            case self::MIME_IMAGE:
                $element = self::getDOMCradle()->createElement('img');
                $element->setAttribute('src', $component->getData());
                return $element;
                break;
            case self::MIME_AUDIO:
                break;
            default:
                // Just parse <object> as default
                return parent::marshallChildrenKnown($component, $elements);
        }
        return parent::marshallChildrenKnown($component, $elements);
    }

    private function getMIMEType($mimeValue)
    {
        if (strpos($mimeValue, 'image') !== false) {
            return self::MIME_IMAGE;
        } elseif (strpos($mimeValue, 'audio') !== false) {
            return self::MIME_AUDIO;
        } elseif (strpos($mimeValue, 'html') !== false) {
            return self::MIME_AUDIO;
        }
        return self::MIME_NOT_SUPPORTED;
    }
}
