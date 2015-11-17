<?php

namespace LearnosityQti\Processors\IMSCP\In;

use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\IMSCP\Entities\Manifest;
use qtism\data\storage\xml\marshalling\Marshaller;

class ManifestMapper
{
    public function parse($xmlString)
    {
        $document = new \DOMDocument();
        $document->preserveWhiteSpace = false;
        $document->loadXML($xmlString);
        $rootElement = $document->documentElement;

        // Manifest mapping start!
        $manifest = new Manifest();
        $manifest->setIdentifier(Marshaller::getDOMElementAttributeAs($rootElement, 'identifier'));

        // Mapping <resource>(s) to Resource model
        $resourcesElement = Marshaller::getChildElementsByTagName($rootElement, 'resources');
        if (!$resourcesElement || count($resourcesElement) !== 1) {
            throw new MappingException('Resources tag must occur once');
        }
        $resourceMapper = new ResourcesMapper();
        $resourcesListElements = Marshaller::getChildElementsByTagName($resourcesElement[0], 'resource');
        $resources = $resourceMapper->map($resourcesListElements);
        $manifest->setResources($resources);

        // TODO: Mapping <organisation>(s) to Organisation model

        // Mapping package Metadata
        $metadataMapper = new MetadataMapper();
        $metadataElement = Marshaller::getChildElementsByTagName($rootElement, 'metadata');

        $manifest->setMetadata($metadataMapper->map($metadataElement[0]));
        return $manifest;
    }
}
