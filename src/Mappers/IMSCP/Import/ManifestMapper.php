<?php

namespace Learnosity\Mappers\IMSCP\Import;

use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\IMSCP\Entities\Dependency;
use Learnosity\Mappers\IMSCP\Entities\File;
use Learnosity\Mappers\IMSCP\Entities\Manifest;
use Learnosity\Mappers\IMSCP\Entities\Resource;
use qtism\data\storage\xml\marshalling\Marshaller;

class ManifestMapper
{
    public function parse($xmlString)
    {
        $manifest = new Manifest();
        $document = new \DOMDocument();
        $document->preserveWhiteSpace = false;
        $document->loadXML($xmlString);
        $rootElement = $document->documentElement;
        $resourcesElement = Marshaller::getChildElementsByTagName($rootElement, 'resources');
        if (!$resourcesElement || count($resourcesElement) !== 1) {
            throw new MappingException('Resources tag must occur once');
        }

        $manifest->setIdentifier(Marshaller::getDOMElementAttributeAs($rootElement, 'identifier'));
        $resourcesListElements = Marshaller::getChildElementsByTagName($resourcesElement[0], 'resource');

        $resources = [];
        foreach ($resourcesListElements as $resourceElement) {
            $resource = new Resource();
            $resource->setHref(Marshaller::getDOMElementAttributeAs($resourceElement, 'href'));
            $resource->setIdentifier(Marshaller::getDOMElementAttributeAs($resourceElement, 'identifier'));
            $resource->setType(Marshaller::getDOMElementAttributeAs($resourceElement, 'type'));

            $fileListElements = Marshaller::getChildElementsByTagName($resourceElement, 'file');

            $files = [];
            if (is_array($fileListElements)) {
                foreach ($fileListElements as $fileElement) {
                    $file = new File();
                    $file->setHref(Marshaller::getDOMElementAttributeAs($fileElement, 'href'));
                    $files[] = $file;
                }
            }
            $resource->setFiles($files);

            $dependencyListElements = Marshaller::getChildElementsByTagName($resourceElement, 'dependency');
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
            $resource->setDependencies($dependencies);

            // resource must have unique id ??
            $resources[$resource->getIdentifier()] = $resource;
        }

        $manifest->setResources($resources);

        $issues = $this->validate($manifest);
        if (count($issues)) {
            return $issues;
        } else {
            return $manifest;
        }
    }

    /**
     * checking if
     * a). all files are available
     * b). dependency are met
     *
     * @param Manifest $manifest
     *
     * @return array
     */
    public function validate(Manifest $manifest)
    {
        $issues = [];
        $resources = $manifest->getResources();

        /* @var $resource Resource */
        foreach ($resources as $resource) {
            $dependencies = $resource->getDependencies();
            if (is_array($dependencies)) {
                /* @var $dependency Dependency */
                foreach ($dependencies as $dependency) {
                    if (!isset($resources[$dependency->getIdentifierref()])) {
                        $issues[] = new MappingException(
                            'Dependency ' . $dependency->getIdentifierref() .
                            ' in resource ' . $resource->getIdentifier() . ' cannot be satisfied.',
                            MappingException::CRITICAL
                        );
                    }
                }
            }
        }
        return $issues;
    }
}
