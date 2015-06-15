<?php

namespace Learnosity\Services;

use Learnosity\Utils\FileSystemUtil;

class SchemasService
{
    private $questionsSchemas;

    public function __construct()
    {
        $schemasDirectory = FileSystemUtil::getRootPath() . '/resources/schemas';
        $this->questionsSchemas = FileSystemUtil::readJsonContent($schemasDirectory . '/questions.json');
    }

    public function getResponsesSchemas()
    {
        return $this->questionsSchemas['data']['responses'];
    }

    public function getFeaturesSchemas()
    {
        return $this->questionsSchemas['data']['features'];
    }

    public function getVersions()
    {
        return $this->questionsSchemas['meta']['schema_version'];
    }
}
