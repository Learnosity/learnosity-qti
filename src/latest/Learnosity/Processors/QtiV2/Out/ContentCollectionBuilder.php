<?php

namespace Learnosity\Processors\QtiV2\Out;

use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\Out\ContentBuilders\BlockCollectionContentBuilder;
use Learnosity\Processors\QtiV2\Out\ContentBuilders\InlineCollectionContentBuilder;
use qtism\data\content\FlowCollection;
use qtism\data\content\FlowStaticCollection;
use qtism\data\QtiComponent;
use qtism\data\QtiComponentCollection;

class ContentCollectionBuilder
{
    public static function buildContent(QtiComponent $component, $content)
    {
        $reflectionClass = new \ReflectionClass($component);

        // TODO: Assumption `setContent` always has content setter on first parameter
        $parameterClass = $reflectionClass->getMethod('setContent')->getParameters()[0]->getClass();
        $contentType = $parameterClass->getShortName();

        try {
            /** @var QtiComponentCollection $content */
            $content = self::validateContent($content);
            if ($contentType === 'InlineCollection') {
                return self::buildInlineCollectionContent($content);
            } elseif ($contentType === 'BlockCollection') {
                return self::buildBlockCollectionContent($content);
            } elseif ($contentType === 'FlowCollection') {
                return self::buildFlowCollectionContent($content);
            } elseif ($contentType === 'FlowStaticCollection') {
                return self::buildFlowStaticCollectionContent($content);
            }
            throw new MappingException('Invalid content');
        } catch (\Exception $e) {
            throw new MappingException('Fail mapping `' . $component->getQtiClassName() . '` - ' .$e->getMessage());
        }
    }

    public static function buildBlockCollectionContent(QtiComponentCollection $content)
    {
        $builder = new BlockCollectionContentBuilder();
        return $builder->buildContentCollection($content);
    }

    public static function buildFlowStaticCollectionContent(QtiComponentCollection $content)
    {
        $collection = new FlowStaticCollection();
        foreach ($content as $component) {
            $collection->attach($component);
        }
        return $collection;
    }

    public static function buildFlowCollectionContent(QtiComponentCollection $content)
    {
        $collection = new FlowCollection();
        foreach ($content as $component) {
            $collection->attach($component);
        }
        return $collection;
    }

    public static function buildInlineCollectionContent(QtiComponentCollection $content)
    {
        $builder = new InlineCollectionContentBuilder();
        return $builder->buildContentCollection($content);
    }

    private static function validateContent($content)
    {
        if (!$content instanceof QtiComponent && !$content instanceof QtiComponentCollection) {
            throw new \InvalidArgumentException('Expected `QtiComponent` or `QtiComponentCollection`');
        }
        if ($content instanceof QtiComponent) {
            $collection = new QtiComponentCollection();
            $collection->attach($content);
            $content = $collection;
        }
        return $content;
    }
}
