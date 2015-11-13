<?php

namespace LearnosityQti\Processors\IMSCP\In;

use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\IMSCP\Entities\Dependency;
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

        // Mapping package Metadata
        $metadataMapper = new MetadataMapper();
        $metadataElement = Marshaller::getChildElementsByTagName($rootElement, 'metadata');

        $manifest->setMetadata(empty($metadataElement) ? [] : $metadataMapper->map($metadataElement[0]));

        // TODO: Fix this
        $errors = $this->validate($manifest);

        return $manifest;
    }

    /**
     * TODO: Not done, we assume everything all good
     * TODO: Checking if
     * a). All files are available
     * b). Dependency are met
     *
     * @param Manifest $manifest
     * @return array
     */
    public function validate(Manifest $manifest)
    {
        $issues = [];
        $resources = $manifest->getResources();

        /* @var $resource Resource */
        foreach ($resources as $resource) {
            /* @var $dependency Dependency */
            foreach ($resource->getDependencies() as $dependency) {
                if (!isset($resources[$dependency->getIdentifierref()])) {
                    $issues[] = new MappingException(
                        'Dependency ' . $dependency->getIdentifierref() .
                        ' in resource ' . $resource->getIdentifier() . ' cannot be satisfied.'
                    );
                }
            }
        }
        return $issues;
    }
}
