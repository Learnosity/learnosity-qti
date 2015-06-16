<?php

namespace Learnosity;

use Learnosity\Services\SchemasService;
use Learnosity\Utils\FileSystemUtil;
use Twig_Extensions_Extension_Text;

class EntityGenerator
{
    private $schemasService;
    private $templateDirectory;

    public function __construct(SchemasService $schemasService)
    {
        $this->templateDirectory = FileSystemUtil::getRootPath() . '/resources/templates';
        $this->outputDir = FileSystemUtil::getRootPath() . '/src/Entities/QuestionTypes';
        $this->schemasService = $schemasService;
    }

    private function cleanUp() {
        @FileSystemUtil::recursiveRemoveDirectory($this->outputDir);
    }

    private function getTwigEnvironment()
    {
        return new \Twig_Environment(new \Twig_Loader_Filesystem($this->templateDirectory), [
            'debug' => true
        ]);
    }

    private function renderFile($template, $target, $parameters)
    {
        $twig = $this->getTwigEnvironment();
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0777, true);
        }
        $parameters['schemasVersion'] = $this->schemasService->getVersions();
        $content = $twig->render($template, $parameters);
        return file_put_contents($target, $content);
    }

    // This would add `fieldType` and `conditional`
    // to attribute for rendering purposes
    private function updateAttribute($attribute, $fieldType)
    {
        if (isset($attribute['type']) && $attribute['type'] === 'array') {
            $attribute['fieldType'] = 'array';
        }
        if (isset($attribute['attributes'])) {
            $attribute['fieldType'] = $fieldType;
        }
        // Chappo says this just an object, no array - might change later :/
        if (isset($attribute['conditional_attributes'])) {
            $key = $attribute['conditional_attributes']['attribute_key'];
            foreach ($attribute['conditional_attributes']['conditions'] as $condition) {
                $conditionValue = $condition['value'];
                foreach ($condition['attributes'] as $k => $v) {
                    // Add the attribute only if required, otherwise use the existing/original
                    //TODO: Need also to mark this attribute with conditional, etc, etc for documentation and validation
                    //TODO: Also whether its always conditional or not always conditional
                    if (!isset($attribute['attributes'][$k])) {
                        $attribute['attributes'][$k] = $v;
                    }
                }
            }
        }
        return $attribute;
    }

    private function generateAttributeClasses($questionId, array &$attributes)
    {
        foreach ($attributes as $key => $attribute) {
            $attributes[$key] = $this->updateAttribute($attribute, $questionId . '_' . $key);

            if (isset($attribute['attributes'])) {
                $attributeId = $questionId . '_' . $key;
                $path = $this->outputDir . DIRECTORY_SEPARATOR . $attributeId . '.php';
                $this->generateAttributeClasses($attributeId, $attribute['attributes']);
                $this->renderFile('entity.php.twig', $path, [
                    'className' => $attributeId,
                    'fields' => $attribute['attributes'],
                    'baseClass' => 'BaseQuestionTypeAttribute'
                ]);
            } elseif (isset($attribute['type']) && $attribute['type'] === 'array') {
                if (isset($attribute['items']['attributes'])) {
                    $t = [$key . '_items' => $attribute['items']];
                    $this->generateAttributeClasses($questionId, $t);
                }
            }
        }
    }

    public function generateQuestionsClasses()
    {
        $this->cleanUp();
        $classes = [];
        $schemas = array_merge($this->schemasService->getResponsesSchemas(), $this->schemasService->getFeaturesSchemas());
        foreach ($schemas as $questionId => $schema) {
            $attributes = $schema['attributes'];
            foreach ($attributes as $key => $attribute) {
                $attributes[$key] = $this->updateAttribute($attribute, $questionId . '_' . $key);
            }
            $this->generateAttributeClasses($questionId, $attributes);
            $classes[$questionId] = [
                'className' => $questionId,
                'requiredFields' => array_filter($attributes, function ($attribute) {
                    return isset($attribute['required']) && $attribute['required'] === true;
                }),
                'fields' => $attributes,
                'baseClass' => 'BaseQuestionType'
            ];
        }

        foreach ($classes as $key => $value) {
            $this->renderFile('entity.php.twig', $this->outputDir . DIRECTORY_SEPARATOR . $value['className'] . '.php', $value);
        }
    }
}
