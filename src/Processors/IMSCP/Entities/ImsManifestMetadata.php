<?php

namespace LearnosityQti\Processors\IMSCP\Entities;

use LearnosityQti\Utils\StringUtil;

class ImsManifestMetadata
{
    private $schema;
    private $schemaversion;
    private $title;
    
    function getSchema() {
        return $this->schema;
    }

    function getSchemaversion() {
        return $this->schemaversion;
    }

    function getTitle() {
        return $this->title;
    }

    function setSchema($schema) {
        $this->schema = $schema;
    }

    function setSchemaversion($schemaversion) {
        $this->schemaversion = $schemaversion;
    }

    function setTitle($title) {
        $this->title = $title;
    }

}
