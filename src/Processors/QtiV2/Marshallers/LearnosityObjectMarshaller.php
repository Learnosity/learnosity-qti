<?php

namespace LearnosityQti\Processors\QtiV2\Marshallers;

use LearnosityQti\Processors\QtiV2\Out\ContentCollectionBuilder;
use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\xhtml\ObjectElement;
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
        /** @var Object $component */
        switch ($this->getMIMEType($component->getType())) {
            case self::MIME_IMAGE:
                $this->checkObjectComponents($component, '<img> tag');
                $element = self::getDOMCradle()->createElement('img');
                $element->setAttribute('src', $component->getData());
                return $element;
                break;
            case self::MIME_AUDIO:
                $this->checkObjectComponents($component, '`audioplayer` feature');
                $element = self::getDOMCradle()->createElement('span');
                $element->setAttribute('class', 'learnosity-feature');
                $element->setAttribute('data-type', 'audioplayer');
                $element->setAttribute('data-src', $component->getData());
                return $element;
                break;
            case self::MIME_VIDEO:
                $this->checkObjectComponents($component, '`videoplayer` feature');
                $element = self::getDOMCradle()->createElement('span');
                $element->setAttribute('class', 'learnosity-feature');
                $element->setAttribute('data-type', 'videoplayer');
                $element->setAttribute('data-src', $component->getData());
                return $element;
                break;
            case self::MIME_HTML:
                $fragment = self::getDOMCradle()->createDocumentFragment();
                $fragment->appendXML(QtiMarshallerUtil::marshallCollection(ContentCollectionBuilder::buildFlowCollectionContent($component->getComponents())));
                $element = self::getDOMCradle()->createElement('div');
                $element->setAttribute('data-type', 'sharedpassage');
                $element->appendChild($fragment);
                return $element;
                break;
            default:
                // TODO: Need to think external HTML object file, what we are going to do with them?
                // Just parse <object> as default
                LogService::log('Unknown <object> MIME type, outputting <object> as it is');
                return parent::marshallChildrenKnown($component, $elements);
        }
    }

    private function checkObjectComponents(Object $object, $conversionTo)
    {
        if (!empty($object->getComponents())) {
            LogService::log('Converting <object> element to ' . $conversionTo . '. Any contents within it are removed');
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
