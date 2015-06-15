<?php

namespace Learnosity;

use Learnosity\Mappers\QtiV2\Import\Interactions\AbstractInteraction;
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

    public function generateInterationDocument()
    {
        $supportedQtiClassName = [
            'choiceInteraction',
        ];

        $interactionDocumentation = [];
        foreach ($supportedQtiClassName as $className) {
            $mapperClass = 'Learnosity\Mappers\QtiV2\Import\Interactions\\' . ucfirst($className);
            /** @var AbstractInteraction $mapperClass */
            $interactionDocumentation[ucfirst($className)] = $mapperClass::getDocumentation();

        }
        $this->renderFile('documentation.html.twig', $this->outputDir . '/interactions.html', [
            'interactions' => $interactionDocumentation
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
