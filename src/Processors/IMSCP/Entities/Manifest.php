<?php

namespace LearnosityQti\Processors\IMSCP\Entities;

class Manifest
{
    protected $identifier;
    protected $resources;
    protected $metadata;
    protected $organizations;
    
    /**
     * Holds the parsed manifest.xml metdata
     *
     * @var string
     */
    protected $manifest;
    
    /**
     * Holds the IMS manifest metadata
     *
     * @var string
     */
    protected $imsManifestMetadata;

    /**
     * @return mixed
     */
    function getImsManifestMetadata()
    {
        return $this->imsManifestMetadata;
    }

    /**
     * @param mixed $imsManifestMetadata
     */
    function setImsManifestMetadata(ImsManifestMetadata $imsManifestMetadata)
    {
        $this->imsManifestMetadata = $imsManifestMetadata;
    }

    /**
     * @return mixed
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param mixed $resources
     */
    public function setResources(array $resources)
    {
        $this->resources = $resources;
    }

    /**
     * @return mixed
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param mixed $metadata
     */
    public function setMetadata(Metadata $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return mixed
     */
    public function getOrganizations()
    {
        return $this->organizations;
    }

    /**
     * @param mixed $organizations
     */
    public function setOrganizations(array $organizations)
    {
        $this->organizations = $organizations;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    public function getManifest()
    {
        return $this->manifest;
    }

    public function setManifest($manifest)
    {
        $this->manifest = $manifest;
    }
}
