<?php

namespace LearnosityQti\Processors\IMSCP\Entities;

class ImsManifestMetadata {

    private $schema;
    private $schemaversion;
    private $title;
    private $qtiMetadata;

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

    function getQtiMetadata() {
        return $this->qtiMetadata;
    }

    function setQtiMetadata($qtiMetadata) {
        $this->qtiMetadata = $qtiMetadata;
    }
}
