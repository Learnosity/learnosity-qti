<?php

namespace Learnosity\Processors\QtiV2\Out;

class Constants
{
    public static $supportedQuestionTypes = [
        'mcq',
        'shorttext',
        'orderlist',
        'longtext',
        'plaintext',
        'choicematrix'
    ];

    const RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT = 'http://www.imsglobal.org/question/qtiv2p1/rptemplates/match_correct.xml';
    const RESPONSE_PROCESSING_TEMPLATE_MAP_RESPONSE = 'http://www.imsglobal.org/question/qtiv2p1/rptemplates/map_response.xml';
}
