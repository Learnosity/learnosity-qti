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

        return $this->parseManifestElement($rootElement);
    }

    public function parseManifestElement(\DOMElement $rootElement)
    {

        // Manifest mapping start!
        $manifest = new Manifest();
        $manifest->setIdentifier(Marshaller::getDOMElementAttributeAs($rootElement, 'identifier'));

        // Mapping <resource>(s) to Resource model
        $resourcesElement = Marshaller::getChildElementsByTagName($rootElement, 'resources');
        if (!empty($resourcesElement)) {
            if (count($resourcesElement) !== 1) {
                throw new MappingException('Resources tag must occur once');
            }
            $resourceMapper = new ResourcesMapper();
            $resourcesListElements = Marshaller::getChildElementsByTagName($resourcesElement[0], 'resource');
            $resources = $resourceMapper->map($resourcesListElements);
            $manifest->setResources($resources);
        }

        // Mapping <organisation>(s) to Organisation model
        $organizationElements = Marshaller::getChildElementsByTagName($rootElement, 'organizations');
        if (!empty($organizationElements)) {
            if (count($organizationElements) !== 1) {
                throw new MappingException('Organisations tag must occur once');
            }
            $organisationsMapper = new OrganizationsMapper();
            $organisationListElements = Marshaller::getChildElementsByTagName($organizationElements[0], 'organization');
            $organisations = $organisationsMapper->map($organisationListElements);
            $manifest->setOrganizations($organisations);
        }

        // Mapping package Metadata
        $metadataElement = Marshaller::getChildElementsByTagName($rootElement, 'metadata');
        if (!empty($metadataElement)) {
            if (count($metadataElement) !== 1) {
                throw new MappingException('Metadata tag must occur once');
            }
            $metadataMapper = new MetadataMapper();
            $manifest->setMetadata($metadataMapper->map($metadataElement[0]));
        }

        // Mapping sub-manifest
        $subManifestElement = Marshaller::getChildElementsByTagName($rootElement, 'manifest');
        if (!empty($subManifestElement)) {
            if (count($subManifestElement) !== 1) {
                throw new MappingException('Manifest tag must occur once');
            }
            $manifest->setManifest($this->parseManifestElement($subManifestElement[0]));
        }

        return $manifest;
    }
}
