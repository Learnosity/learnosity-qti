<?php

namespace LearnosityQti;

use Exception;
use LearnosityQti\Services\SchemasService;
use LearnosityQti\Utils\FileSystemUtil;
use Twig_Extension_Debug;
use Twig_Extensions_Extension_Text;

class EntityGenerator
{
    private SchemasService $schemasService;
    private string $templateDirectory;
    private string $currentNamespace;
    private string $questionOutputDir;
    private string $itemOutputDir;
    private string $activityOutputDir;

    public function __construct(SchemasService $schemasService)
    {
        $this->templateDirectory = FileSystemUtil::getRootPath() . '/Config/resources/templates';
        $this->questionOutputDir = FileSystemUtil::getRootPath() . '/Entities/QuestionTypes';
        $this->itemOutputDir = FileSystemUtil::getRootPath() . '/Entities/Item';
        $this->activityOutputDir = FileSystemUtil::getRootPath() . '/Entities/Activity';

        $this->schemasService = $schemasService;
        $this->currentNamespace = ''; //TODO: Fix this hack properly!
    }

    private function cleanUp($path): void
    {
        @FileSystemUtil::removeDir($path);
    }

    private function renderFile($template, $target, $parameters): void
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem($this->templateDirectory), [
            'debug' => true
        ]);
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        $twig->addExtension(new Twig_Extension_Debug());

        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0777, true);
        }
        $parameters['schemasVersion'] = $this->schemasService->getVersions();
        $parameters['namespace'] = $this->currentNamespace;
        $content = $twig->render($template, $parameters);
        file_put_contents($target, $content);
    }

    // This would add `fieldType` and `conditional`
    // to attribute for rendering purposes
    /**
     * @throws Exception
     */
    private function updateAttribute($attribute, $fieldType)
    {
        if (isset($attribute['type']) && $attribute['type'] === 'array') {
            $attribute['fieldType'] = 'array';
        }
        if (isset($attribute['attributes'])) {
            $attribute['fieldType'] = $fieldType;
        }
        if (isset($attribute['conditional_attributes'])) {
            // Chappo used to says that this is just an object
            // And now, it sometimes can be object and can be array :'(
            if ($this->isAssociativeArray($attribute['conditional_attributes'])) {
                $conditions = $attribute['conditional_attributes']['conditions'];
            } else {
                // Now assume the content array count is always one
                // If not, then we will need to fix the code
                if (count($attribute['conditional_attributes']) > 1) {
                    throw new Exception('Need to fix this code to handle conditional multiple attributes');
                }
                $conditions = $attribute['conditional_attributes'][0]['conditions'];
            }
            foreach ($conditions as $condition) {
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

    private function isAssociativeArray(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * @throws Exception
     */
    private function generateAttributeClasses($outputDir, $identifier, array &$attributes): void
    {
        foreach ($attributes as $key => $attribute) {
            $attributes[$key] = $this->updateAttribute($attribute, $identifier . '_' . $key);

            if (isset($attribute['attributes'])) {
                $attributeId = $identifier . '_' . $key;
                $path = $outputDir . DIRECTORY_SEPARATOR . $attributeId . '.php';
                $this->generateAttributeClasses($outputDir, $attributeId, $attribute['attributes']);
                $this->renderFile('entity.php.twig', $path, [
                    'className' => $attributeId,
                    'fields' => $attribute['attributes'],
                    'baseClass' => 'BaseQuestionTypeAttribute'
                ]);
            } elseif (isset($attribute['type']) && $attribute['type'] === 'array') {
                if (isset($attribute['items']['attributes'])) {
                    $t = [$key . '_item' => $attribute['items']];
                    $this->generateAttributeClasses($outputDir, $identifier, $t);
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    private function generateClasses(array $schemas, $outputDir, $baseClass): void
    {
        $classes = [];
        foreach ($schemas as $identifier => $schema) {
            $attributes = $schema['attributes'];
            foreach ($attributes as $key => $attribute) {
                $attributes[$key] = $this->updateAttribute($attribute, $identifier . '_' . $key);
            }
            $this->generateAttributeClasses($outputDir, $identifier, $attributes);
            $classes[$identifier] = [
                'className'      => $identifier,
                'requiredFields' => array_filter($attributes, function ($attribute) {
                    return isset($attribute['required']) && $attribute['required'] === true;
                }),
                'fields'         => $attributes,
                'baseClass'      => $baseClass,
                // TODO: this is questions schemas only to populate `widget_type`. Need tidy up!
                'widgetType'     => $schema['type'] ?? null
            ];
        }
        foreach ($classes as $value) {
            $this->renderFile(
                'entity.php.twig',
                $outputDir . DIRECTORY_SEPARATOR . $value['className'] . '.php',
                $value
            );
        }
    }

    /**
     * @throws Exception
     */
    public function generateQuestionsClasses()
    {
        $this->cleanUp($this->questionOutputDir);
        $this->currentNamespace = 'LearnosityQti\Entities\QuestionTypes';
        $schemas = array_merge(
            $this->schemasService->getResponsesSchemas(),
            $this->schemasService->getFeaturesSchemas()
        );
        $this->generateClasses($schemas, $this->questionOutputDir, 'BaseQuestionType');
    }

    /**
     * @throws Exception
     */
    public function generateItemClasses()
    {
        $this->cleanUp($this->itemOutputDir);
        $this->currentNamespace = 'LearnosityQti\Entities\Item';
        $schemas = $this->schemasService->getItemSchemas();
        $this->generateClasses($schemas, $this->itemOutputDir, 'BaseEntity');
    }

    /**
     * @throws Exception
     */
    public function generateActivityClasses()
    {
        $this->cleanUp($this->activityOutputDir);
        $this->currentNamespace = 'LearnosityQti\Entities\Activity';
        $schemas = $this->schemasService->getActivitySchemas();
        $this->generateClasses($schemas, $this->activityOutputDir, 'BaseEntity');
    }
}
