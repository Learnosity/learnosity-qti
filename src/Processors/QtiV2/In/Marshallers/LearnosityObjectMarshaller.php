<?php

namespace Learnosity\Processors\QtiV2\In\Marshallers;

use qtism\data\QtiComponent;
use qtism\data\storage\xml\marshalling\ObjectMarshaller;

class LearnosityObjectMarshaller extends ObjectMarshaller
{
    const MIME_IMAGE = 'image';
    const MIME_AUDIO = 'audio';
    const MIME_VIDEO = 'video';
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
                $element = self::getDOMCradle()->createElement('span');
                $element->setAttribute('class', 'learnosity-feature');
                $element->setAttribute('data-type', 'audioplayer');
                $element->setAttribute('data-src', $component->getData());
                return $element;
                break;
            case self::MIME_VIDEO:
                $element = self::getDOMCradle()->createElement('span');
                $element->setAttribute('class', 'learnosity-feature');
                $element->setAttribute('data-type', 'videoplayer');
                $element->setAttribute('data-src', $component->getData());
                return $element;
                break;
            default:
                // Just parse <object> as default
                return parent::marshallChildrenKnown($component, $elements);
        }
    }

    private function getMIMEType($mimeValue)
    {
        if (strpos($mimeValue, 'image') !== false) {
            return self::MIME_IMAGE;
        } elseif (strpos($mimeValue, 'audio') !== false) {
            return self::MIME_AUDIO;
        } elseif (strpos($mimeValue, 'video') !== false) {
                return self::MIME_VIDEO;
        } elseif (strpos($mimeValue, 'html') !== false) {
            return self::MIME_HTML;
        }
        return self::MIME_NOT_SUPPORTED;
    }
}
