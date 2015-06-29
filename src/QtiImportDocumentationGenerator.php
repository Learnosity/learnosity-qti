<?php

namespace Learnosity;

use Learnosity\Mappers\QtiV2\Import\Documentation\AssessmentItemDocumentation;
use Learnosity\Mappers\QtiV2\Import\Documentation\InteractionDocumentationInterface;
use Learnosity\Services\SchemasService;
use Learnosity\Utils\FileSystemUtil;
use Twig_Extension_Debug;
use Twig_Extensions_Extension_Text;

class QtiImportDocumentationGenerator
{
    private $twig;
    private $documentationPath;
    private $schemasService;

    public function __construct(SchemasService $schemasService)
    {
        $templateDirectory = FileSystemUtil::getRootPath() . '/resources/templates';
        $this->documentationPath = FileSystemUtil::getRootPath() . '/examples/documentation.html';
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem($templateDirectory), [
            'debug' => true
        ]);
        $this->twig->addExtension(new Twig_Extensions_Extension_Text());
        $this->twig->addExtension(new Twig_Extension_Debug());
        $this->schemasService = $schemasService;
    }

    public function generateDocumentation()
    {
        // Generate interaction documentation
        //TODO: Need to store this somewhere
        $supportedQtiClassName = [
            'choiceInteraction',
            'textEntryInteraction',
            'extendedTextInteraction',
            'inlineChoiceInteraction',
            'hottextInteraction'
        ];
        $interactionDocumentation = [];
        foreach ($supportedQtiClassName as $className) {
            /** @var InteractionDocumentationInterface $mapperClass */
            $mapperClass = 'Learnosity\Mappers\QtiV2\Import\Documentation\Interactions\\' . ucfirst($className) . 'Documentation';
            $interactionDocumentation[ucfirst($className)] = [
                'interactionMapping' => $mapperClass::getInteractionDocumentation()
            ];
        }

        // Render
        $this->renderFile('documentation.html.twig', $this->documentationPath, [
            'interactions' => $interactionDocumentation,
            'assessmentItem' => AssessmentItemDocumentation::getInteractionDocumentation(),
            'itemSchemas' => $this->schemasService->getItemSchemas()
        ]);
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
