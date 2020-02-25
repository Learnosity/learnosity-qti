<?php

namespace LearnosityQti\Processors\QtiV2\In;

class Constants
{
    public static $supportedInteractions = [
        'inlineChoiceInteraction',
        'choiceInteraction',
        'extendedTextInteraction',
        'textEntryInteraction',
        'matchInteraction',
        'hottextInteraction',
        'gapMatchInteraction',
        'orderInteraction',
        'graphicGapMatchInteraction',
        'hotspotInteraction',
        'mediaInteraction'
    ];

    public static $needMergeInteractionTypes = [
        'textEntryInteraction',
        'inlineChoiceInteraction'
    ];

    public static $unsupportedInteractions = [
        'drawingInteraction'
    ];
    
    public static $baseLearnosityAssetsUrl = 'https://assets.learnosity.com/organisations/';

}
