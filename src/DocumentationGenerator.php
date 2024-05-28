<?php

namespace LearnosityQti;

use LearnosityQti\Processors\QtiV2\In\Constants as QtiImportConstant;
use LearnosityQti\Processors\QtiV2\Out\Constants as QtiExportConstant;
use LearnosityQti\Processors\QtiV2\In\Documentation\AssessmentItemDocumentation;
use LearnosityQti\Processors\QtiV2\In\Documentation\InteractionDocumentationInterface;
use LearnosityQti\Processors\QtiV2\Out\Documentation\LearnosityDoc;
use LearnosityQti\Processors\QtiV2\Out\Documentation\QuestionTypeDocumentationInterface;
use LearnosityQti\Services\SchemasService;
use LearnosityQti\Utils\FileSystemUtil;
use LearnosityQti\Utils\StringUtil;
use Twig_Extension_Debug;
use Twig_Extensions_Extension_Text;

class DocumentationGenerator
{
    private \Twig_Environment $twig;
    private string $documentationPath;
    private SchemasService $schemasService;

    public function __construct(SchemasService $schemasService)
    {
        $templateDirectory = FileSystemUtil::getRootPath() . '/Config/resources/templates';
        $this->documentationPath = FileSystemUtil::getRootPath() . '/../docs/documentation.html';
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem($templateDirectory), [
            'debug' => true
        ]);
        $this->twig->addExtension(new Twig_Extensions_Extension_Text());
        $this->twig->addExtension(new Twig_Extension_Debug());
        $this->schemasService = $schemasService;
    }

    public function generateDocumentation(): void
    {
        $this->renderFile('documentation.html.twig', $this->documentationPath, $this->generateDocumentationData());
    }

    public function generateDocumentationData(): array
    {
        $learnosityToQtiDocumenation = $this->generateLearnosityToQtiDocumentation();
        $qtiToLearnosityDocumentation = $this->generateQtiToLearnosityDocumentation();

        return [
            'learnosityToQti' => $learnosityToQtiDocumenation,
            'qtiToLearnosity' => $qtiToLearnosityDocumentation
        ];
    }

    private function generateLearnosityToQtiDocumentation(): array
    {
        $questionTypeDocumentation = [];
        $responsesSchemas = $this->schemasService->getResponsesSchemas();
        foreach ($responsesSchemas as $questionType => $data) {
            if (in_array($questionType, QtiExportConstant::$supportedQuestionTypes)) {
                /** @var QuestionTypeDocumentationInterface $mapperClass */
                $mapperClass = 'LearnosityQti\Processors\QtiV2\Out\Documentation\QuestionTypes\\' . ucfirst($questionType) . 'Documentation';
                $documentation = $mapperClass::getDocumentation();
                foreach (array_keys($this->generateAttributeTable($data['attributes'])) as $flattenedAttributeName) {
                    // TODO: Need to check new or non-existing attribute name in case our schemas change
                    if (!in_array($flattenedAttributeName, array_keys($documentation))) {
                        $documentation[$flattenedAttributeName] = LearnosityDoc::none();
                    }
                }
                // TODO: Hack here, hide all the `validation` attributes
                $documentationToDisplay = [];
                foreach ($documentation as $attributeName => $doc) {
                    if (!StringUtil::startsWith($attributeName, 'validation')) {
                        $documentationToDisplay[$attributeName] = $doc;
                    }
                }
                $questionTypeDocumentation[$questionType] = [
                    'mapping' => $documentationToDisplay,
                    'introduction' => $mapperClass::getIntroductionNotes()
                ];
            }
        }

        return [
            'questionTypes' => $questionTypeDocumentation,
            'unsupportedQuestionTypes' => array_keys(array_diff_key($responsesSchemas, $questionTypeDocumentation))
        ];
    }

    private function generateAttributeTable(
        array $attributes,
        $prefix = ''
    ): array {
        $result = [];

        foreach ($attributes as $name => $attribute) {
            $flattenedName = empty($prefix) ? $name : $prefix . '.' . $name;
            // If normal data type
            $result[$flattenedName] = $attribute;
            // TODO: Type not set (wtf~)
            // TODO: Broken schemas, fix it goddammit
            if (!isset($attribute['type'])) {
                continue;
            }
            // TODO: Another broken schemas
            // TODO: array but items is not set
            if ($attribute['type'] === 'array' && !isset($attribute['items'])) {
                continue;
            }
            // If array then look for its items then recurse
            if ($attribute['type'] === 'array' && $attribute['items']['type'] === 'object' && isset($attribute['items']['attributes'])) {
                $result = array_merge($result, $this->generateAttributeTable($attribute['items']['attributes'], $flattenedName . '[]'));
            } elseif ($attribute['type'] === 'object' && isset($attribute['attributes'])) {
                $result = array_merge($result, $this->generateAttributeTable($attribute['attributes'], $flattenedName));
            }
        }

        return $result;
    }

    private function generateQtiToLearnosityDocumentation(): array
    {
        // Generate interaction documentation
        $interactionDocumentation = [];
        foreach (QtiImportConstant::$supportedInteractions as $className) {
            /** @var InteractionDocumentationInterface $mapperClass */
            $mapperClass = 'LearnosityQti\Processors\QtiV2\In\Documentation\Interactions\\' . ucfirst($className) . 'Documentation';
            $interactionDocumentation[ucfirst($className)] = [
                'interactionMapping' => $mapperClass::getInteractionDocumentation()
            ];
        }

        return [
            'interactions' => $interactionDocumentation,
            'assessmentItem' => AssessmentItemDocumentation::getInteractionDocumentation(),
            'itemSchemas' => $this->schemasService->getItemSchemas()
        ];
    }

    private function renderFile($template, $target, $parameters): void
    {
        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0777, true);
        }

        $content = $this->twig->render($template, $parameters);
        file_put_contents($target, $content);
    }
}
