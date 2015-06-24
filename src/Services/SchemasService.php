<?php

namespace Learnosity\Services;

use Learnosity\Utils\FileSystemUtil;

class SchemasService
{
    private $questionsSchemas;
    private $activitySchemas;
    private $itemSchemas;

    public function __construct()
    {
        $schemasDirectory = FileSystemUtil::getRootPath() . '/resources/schemas';
        $this->questionsSchemas = FileSystemUtil::readJsonContent($schemasDirectory . '/questions.json');
        $this->itemSchemas = FileSystemUtil::readJsonContent($schemasDirectory . '/item.json');
        $this->activitySchemas = FileSystemUtil::readJsonContent($schemasDirectory . '/activity.json');
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

    public function getActivitySchemas()
    {
        return $this->activitySchemas['data'];
    }

    public function getItemSchemas()
    {
        return $this->itemSchemas['data'];
    }
}
