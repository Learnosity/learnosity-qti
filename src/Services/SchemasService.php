<?php

namespace LearnosityQti\Services;

use Exception;
use LearnosityQti\Utils\FileSystemUtil;

class SchemasService
{
    private mixed $questionsSchemas;
    private mixed $activitySchemas;
    private mixed $itemSchemas;
    private mixed $htmlSchemas;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $schemasDirectory = FileSystemUtil::getRootPath() . '/Config/resources/schemas';
        $this->questionsSchemas = FileSystemUtil::readJsonContent($schemasDirectory . '/questions.json');
        $this->itemSchemas = FileSystemUtil::readJsonContent($schemasDirectory . '/item.json');
        $this->activitySchemas = FileSystemUtil::readJsonContent($schemasDirectory . '/activity.json');
        $this->htmlSchemas = FileSystemUtil::readJsonContent($schemasDirectory . '/html.json');
    }

    public function getResponsesSchemas()
    {
        // TODO: Hack here because Chappo hide `tokenization` for texthighlight in QE and always set to `custom`
        $this->questionsSchemas['data']['responses']['tokenhighlight']['attributes']['tokenization'] = [
            'description' => 'This need to always be set to `custom`',
            'required' => true,
            'default' => 'custom',
            'type' => 'string'
        ];
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

    /** @noinspection PhpUnused */
    public function getHtmlSchemas()
    {
        return $this->htmlSchemas['data'];
    }
}
