<?php

namespace LearnosityQti\Processors\IMSCP\In;

use LearnosityQti\Processors\IMSCP\Entities\Dependency;
use LearnosityQti\Processors\IMSCP\Entities\File;
use LearnosityQti\Processors\IMSCP\Entities\Resource;
use qtism\data\storage\xml\Utils as XmlUtils;

class ResourcesMapper
{
    /**
     * Parse an array of DOMElement to array of Resource models
     *
     * @param [DOMElement]  $resourcesListElements
     * @return array
     */
    public function map(array $resourcesListElements)
    {
        $resources = [];
        foreach ($resourcesListElements as $resourceElement) {
            $resource = new Resource();
            $resource->setHref(XmlUtils::getDOMElementAttributeAs($resourceElement, 'href'));
            $resource->setIdentifier(XmlUtils::getDOMElementAttributeAs($resourceElement, 'identifier'));
            $resource->setType(XmlUtils::getDOMElementAttributeAs($resourceElement, 'type'));

            // Mapping to File models
            $fileListElements = XmlUtils::getChildElementsByTagName($resourceElement, 'file');
            $resource->setFiles($this->mapFileElements($fileListElements));

            // Mapping to Dependency models
            $dependencyListElements = XmlUtils::getChildElementsByTagName($resourceElement, 'dependency');
            $resource->setDependencies($this->mapDependencyElements($dependencyListElements));

            // Mapping its metadata
            $metadataListElements = XmlUtils::getChildElementsByTagName($resourceElement, 'metadata');
            if (!empty($metadataListElements)) {
                $metadataMapper = new MetadataMapper();
                $flattenedMetadatas = $metadataMapper->map($metadataListElements[0]);
                $resource->setMetadata($flattenedMetadatas);
            }

            // Resource must have unique id ??
            $resources[$resource->getIdentifier()] = $resource;
        }
        return $resources;
    }

    /**
     * Parse an array of DOMElement to array of File models
     *
     * @param [DOMElement]  $fileListElements
     * @return array
     */
    public function mapFileElements(array $fileListElements)
    {
        $files = [];
        if (is_array($fileListElements)) {
            foreach ($fileListElements as $fileElement) {
                $file = new File();
                $file->setHref(XmlUtils::getDOMElementAttributeAs($fileElement, 'href'));
                $files[] = $file;
            }
        }
        return $files;
    }

    /**
     * Parse an array of DOMElement to array of Dependency models
     *
     * @param [DOMElement]  $dependencyListElements
     * @return array
     */
    public function mapDependencyElements(array $dependencyListElements)
    {
        $dependencies = [];
        if (is_array($dependencyListElements)) {
            foreach ($dependencyListElements as $dependencyElement) {
                $dependency = new Dependency();
                $dependency->setIdentifierref(
                    XmlUtils::getDOMElementAttributeAs($dependencyElement, 'identifierref')
                );
                $dependencies[] = $dependency;
            }
        }
        return $dependencies;
    }
}
