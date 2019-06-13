<?php

namespace LearnosityQti\Processors\IMSCP\Entities;

class ImsManifestMetadata
{
    private $schema;
    private $schemaVersion;
    private $title;
    private $qtiMetadata;

    function getSchema()
    {
        return $this->schema;
    }

    function getSchemaVersion()
    {
        return $this->schemaVersion;
    }

    function getTitle()
    {
        return $this->title;
    }

    function setSchema($schema)
    {
        $this->schema = $schema;
    }

    function setSchemaVersion($schemaVersion)
    {
        $this->schemaVersion = $schemaVersion;
    }

    function setTitle($title)
    {
        $this->title = $title;
    }

    function getQtiMetadata()
    {
        return $this->qtiMetadata;
    }

    function setQtiMetadata($qtiMetadata)
    {
        $this->qtiMetadata = $qtiMetadata;
    }
}
