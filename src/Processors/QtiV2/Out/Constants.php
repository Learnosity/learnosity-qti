<?php

namespace LearnosityQti\Processors\QtiV2\Out;

class Constants
{
    private static $inputPath;

    public static $supportedQuestionTypes = [
        'mcq',
        'shorttext',
        'orderlist',
        'longtext',
        'longtextV2',
        'plaintext',
        'choicematrix',
        'tokenhighlight',
        'clozeassociation',
        'clozetext',
        'clozedropdown',
        'imageclozeassociation',
        'imageclozeassociationV2',
        'hotspot'
    ];

    public static $supportedFeatureTypes = [
        'videoplayer',
        'audioplayer'
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
        'clozeassociation',
        'imageclozeassociationV2',
    ];


    public static function setInputPath($value)
    {
        self::$inputPath = $value;
    }

    public static function getInputPath()
    {
        return self::$inputPath;
    }

    const RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT = 'http://www.imsglobal.org/question/qti_v2p1/rptemplates/match_correct';
    const RESPONSE_PROCESSING_TEMPLATE_MAP_RESPONSE = 'http://www.imsglobal.org/question/qti_v2p1/rptemplates/map_response';
    const IMSQTI_TOOLNAME = 'Learnosity QTI';
    const IMSQTI_TOOL_VERSION = '1.0';
    const IMSQTI_TOOL_VENDOR = 'Learnosity';
    const IMSQTI_TITLE = 'Learnosity Item Bank Package';
    const IMSQTI_LANG = 'en';
    const IMSQTI_METADATA_SCHEMA = array('LOMv1.0', 'QTIv2.1');
    const SCHEMA_NAME = 'QTIv2.1 Item Bank Package';
    const SCHEMA_VERSION = '2.1';
    const DIRNAME_ITEMS = 'items';
    const DIRNAME_AUDIO = '../audio';
    const DIRNAME_IMAGES = '../images';
    const SHARED_PASSAGE_FOLDER_NAME = '../passages';
    const DIRNAME_VIDEO = '../video';
    const SHARED_PASSAGE_DIV_CLASS = 'col-xs-6';
    const DIRPATH_ASSETS = '/vendor/learnosity/itembank/assets/';
}
