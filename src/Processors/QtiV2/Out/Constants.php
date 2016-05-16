<?php

namespace LearnosityQti\Processors\QtiV2\Out;

class Constants
{
    public static $supportedQuestionTypes = [
        'mcq',
        'shorttext',
        'orderlist',
        'longtext',
        'plaintext',
        'choicematrix',
        'tokenhighlight',
        'clozeassociation',
        'clozetext',
        'clozedropdown',
        'imageclozeassociation',
        'hotspot'
    ];

    /**
     * Note that only question types that maps to response declaration with `single` baseType would be able to be mapped with `mapping`.
     * Ignored for now :
     *  - mcq (can be both `single` and `multiple`, can only support when `single`)
     *  - tokenhighlight (can be both `single` and `multiple`, can only support when `single`)
     *  - clozedropdown
     */
    public static $questionTypesWithMappingSupport = [
        'shorttext',
        'clozetext',
    ];

    const RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT = 'http://www.imsglobal.org/question/qtiv2p1/rptemplates/match_correct.xml';
    const RESPONSE_PROCESSING_TEMPLATE_MAP_RESPONSE = 'http://www.imsglobal.org/question/qtiv2p1/rptemplates/map_response.xml';
}
