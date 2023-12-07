<?php

namespace LearnosityQti\Processors\QtiV2\In;

use \qtism\data\processing\ResponseProcessing;
use \AllowDynamicProperties;

#[AllowDynamicProperties]
class ResponseProcessingTemplate
{
    const MATCH_CORRECT = 'match_correct';
    const MAP_RESPONSE = 'map_response';
    const CC2_MAP_RESPONSE = 'cc2_map_response';
    const MAP_RESPONSE_POINT = 'map_response_point';
    const NONE = 'none';
    const UNSUPPORTED = 'unsupported';
    const BUILTIN = 'builtin';

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
        } elseif ($template === self::MAP_RESPONSE) {
            return self::mapResponse();
        } elseif ($template === self::CC2_MAP_RESPONSE) {
            return self::cc2ResponseMap();
        } elseif ($template === self::MAP_RESPONSE_POINT) {
            return self::mapResponsePoint();
        }
        return self::unsupported();
    }

    public static function none()
    {
        return new self(self::NONE);
    }

    public static function unsupported()
    {
        return new self(self::UNSUPPORTED);
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

    public static function builtin(ResponseProcessing $responseProcessing)
    {
        $instance = new static(static::BUILTIN);
        $instance->setBuiltinResponseProcessing($responseProcessing);

        return $instance;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setBuiltinResponseProcessing(ResponseProcessing $responseProcessing)
    {
        $this->responseProcessing = $responseProcessing;
    }

    public function getBuiltinResponseProcessing()
    {
        return $this->responseProcessing;
    }
}
