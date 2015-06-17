<?php

namespace Learnosity;

use Learnosity\Mappers\QtiV2\Import\Documentation\AssessmentItemDocumentation;
use Learnosity\Mappers\QtiV2\Import\Documentation\InteractionDocumentationInterface;
use Learnosity\Utils\FileSystemUtil;

class QtiImportDocumentationGenerator
{
    private $twig;
    private $outputDir;

    public function __construct()
    {
        $templateDirectory = FileSystemUtil::getRootPath() . '/resources/templates';
        $this->outputDir = FileSystemUtil::getRootPath() . '/docs';
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
        $this->renderFile('documentation.html.twig', $this->outputDir . '/interactions.html', [
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
