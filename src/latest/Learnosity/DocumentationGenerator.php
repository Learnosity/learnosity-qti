<?php

namespace Learnosity;

use Learnosity\Processors\QtiV2\In\Constants as QtiImportConstant;
use Learnosity\Processors\QtiV2\Out\Constants as QtiExportConstant;
use Learnosity\Processors\QtiV2\In\Documentation\AssessmentItemDocumentation;
use Learnosity\Processors\QtiV2\In\Documentation\InteractionDocumentationInterface;
use Learnosity\Processors\QtiV2\Out\Documentation\LearnosityDoc;
use Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypeDocumentationInterface;
use Learnosity\Services\SchemasService;
use Learnosity\Utils\FileSystemUtil;
use Twig_Extension_Debug;
use Twig_Extensions_Extension_Text;

class DocumentationGenerator
{
    private $twig;
    private $documentationPath;
    private $schemasService;

    public function __construct(SchemasService $schemasService)
    {
        $templateDirectory = FileSystemUtil::getRootPath() . '/Config/resources/templates';
        $this->documentationPath = FileSystemUtil::getRootPath() . '/../examples/documentation.html';
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem($templateDirectory), [
            'debug' => true
        ]);
        $this->twig->addExtension(new Twig_Extensions_Extension_Text());
        $this->twig->addExtension(new Twig_Extension_Debug());
        $this->schemasService = $schemasService;
    }

    public function generateDocumentation()
    {
        $learnosityToQtiDocumenation = $this->generateLearnosityToQtiDocumentation();
        $qtiToLearnosityDocumentation = $this->generateQtiToLearnosityDocumentation();

        $this->renderFile('documentation.html.twig', $this->documentationPath, [
            'learnosityToQti' => $learnosityToQtiDocumenation,
            'qtiToLearnosity' => $qtiToLearnosityDocumentation
        ]);
    }

    private function generateLearnosityToQtiDocumentation()
    {
        $questionTypeDocumentation = [];
        $responsesSchemas = $this->schemasService->getResponsesSchemas();
        foreach ($responsesSchemas as $questionType => $data) {
            if (in_array($questionType, QtiExportConstant::$supportedQuestionTypes)) {
                /** @var QuestionTypeDocumentationInterface $mapperClass */
                $mapperClass = 'Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypes\\' . ucfirst($questionType) . 'Documentation';
                $documentation = $mapperClass::getDocumentation();
                foreach (array_keys($this->generateAtributeTable($data['attributes'])) as $flattenedAttributeName) {
                    // TODO: Need to check new or non-existing attribute name in case our schemas change
                    if (!in_array($flattenedAttributeName, array_keys($documentation))) {
                        $documentation[$flattenedAttributeName] = LearnosityDoc::none();
                    }
                }
                $questionTypeDocumentation[$questionType] = [
                    'mapping' => $documentation,
                    'introduction' => $mapperClass::getIntroductionNotes()
                ];
            }
        }
        return [
            'questionTypes' => $questionTypeDocumentation,
            'unsupportedQuestionTypes' => array_keys(array_diff_key($responsesSchemas, $questionTypeDocumentation))
        ];
    }

    private function generateAtributeTable(array $attributes, $prefix = '')
    {
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
            // If array then look for its items then recurse
            if ($attribute['type'] === 'array' && $attribute['items']['type'] === 'object' && isset($attribute['items']['attributes'])) {
                $result = array_merge($result, $this->generateAtributeTable($attribute['items']['attributes'], $flattenedName . '[]'));
            } elseif ($attribute['type'] === 'object' && isset($attribute['attributes'])) {
                $result = array_merge($result, $this->generateAtributeTable($attribute['attributes'], $flattenedName));
            }
        }
        return $result;
    }

    private function generateQtiToLearnosityDocumentation()
    {
        // Generate interaction documentation
        $interactionDocumentation = [];
        foreach (QtiImportConstant::$supportedInteractions as $className) {
            /** @var InteractionDocumentationInterface $mapperClass */
            $mapperClass = 'Learnosity\Processors\QtiV2\In\Documentation\Interactions\\' . ucfirst($className) . 'Documentation';
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

    private function renderFile($template, $target, $parameters)
    {
        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0777, true);
        }
        $content = $this->twig->render($template, $parameters);
        return file_put_contents($target, $content);
    }
}
