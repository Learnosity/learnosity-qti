<?php

namespace Learnosity;

use Learnosity\Mappers\QtiV2\Import\Documentation\AssessmentItemDocumentation;
use Learnosity\Mappers\QtiV2\Import\Documentation\InteractionDocumentationInterface;
use Learnosity\Utils\FileSystemUtil;

class QtiImportDocumentationGenerator
{
    private $twig;
    private $documentationPath;

    public function __construct()
    {
        $templateDirectory = FileSystemUtil::getRootPath() . '/resources/templates';
        $this->documentationPath = FileSystemUtil::getRootPath() . '/examples/documentation.html';
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem($templateDirectory), [
            'debug' => true
        ]);
    }

    public function generateDocumentation()
    {
        // Generate interaction documentation
        //TODO: Need to store this somewhere
        $supportedQtiClassName = [
            'choiceInteraction',
            'textEntryInteraction',
            'extendedTextInteraction',
            'inlineChoiceInteraction'
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
            'assessmentItem' => AssessmentItemDocumentation::getInteractionDocumentation()
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
