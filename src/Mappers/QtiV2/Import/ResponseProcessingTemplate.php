<?php

namespace Learnosity\Mappers\QtiV2\Import;

class ResponseProcessingTemplate
{
    const MATCH_CORRECT = 'match_correct';
    const MAP_RESPONSE = 'map_response';
    const CC2_MAP_RESPONSE = 'cc2_map_response';
    const MAP_RESPONSE_POINT = 'map_response_point';

    private $template;

    private function __construct($template)
    {
        $this->template = $template;
    }

    public static function getFromTemplateUrl($url)
    {
        $template = strtolower(basename($url, '.xml'));
        if ($template === self::MATCH_CORRECT) {
            return self::matchCorrect();
        } else if ($template === self::MAP_RESPONSE) {
            return self::mapResponse();
        } else if ($template === self::CC2_MAP_RESPONSE) {
            return self::cc2ResponseMap();
        } else if ($template === self::MAP_RESPONSE_POINT) {
            return self::mapResponsePoint();
        }
        return null;
    }

    public static function matchCorrect()
    {
        return new self(self::MATCH_CORRECT);
    }

    public static function mapResponse()
    {
        return new self(self::MAP_RESPONSE);
    }

    private static function cc2ResponseMap()
    {
        return new self(self::CC2_MAP_RESPONSE);
    }

    public static function mapResponsePoint()
    {
        return new self(self::MAP_RESPONSE_POINT);
    }

    public function getTemplate()
    {
        return $this->template;
    }
}