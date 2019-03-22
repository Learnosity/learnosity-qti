<?php

namespace LearnosityQti\Processors\IMSCP\In;

use LearnosityQti\Processors\IMSCP\Entities\Dependency;
use LearnosityQti\Processors\IMSCP\Entities\File;
use LearnosityQti\Processors\IMSCP\Entities\Resource;
use qtism\data\storage\xml\marshalling\Marshaller;

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
            $resource->setHref(Marshaller::getDOMElementAttributeAs($resourceElement, 'href'));
            $resource->setIdentifier(Marshaller::getDOMElementAttributeAs($resourceElement, 'identifier'));
            $resource->setType(Marshaller::getDOMElementAttributeAs($resourceElement, 'type'));

            // Mapping to File models
            $fileListElements = Marshaller::getChildElementsByTagName($resourceElement, 'file');
            $resource->setFiles($this->mapFileElements($fileListElements));

            // Mapping to Dependency models
            $dependencyListElements = Marshaller::getChildElementsByTagName($resourceElement, 'dependency');
            $resource->setDependencies($this->mapDependencyElements($dependencyListElements));

            // Mapping its metadata
            $metadataListElements = Marshaller::getChildElementsByTagName($resourceElement, 'metadata');
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
                $file->setHref(Marshaller::getDOMElementAttributeAs($fileElement, 'href'));
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
                    Marshaller::getDOMElementAttributeAs($dependencyElement, 'identifierref')
                );
                $dependencies[] = $dependency;
            }
        }
        return $dependencies;
    }
}
