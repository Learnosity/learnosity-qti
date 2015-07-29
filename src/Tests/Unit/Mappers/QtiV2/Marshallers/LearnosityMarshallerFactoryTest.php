<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Marshallers;

use Learnosity\AppContainer;
use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use Learnosity\Processors\QtiV2\Marshallers\LearnosityMarshallerFactory;
use Learnosity\Services\SchemasService;
use qtism\data\storage\xml\marshalling\UnmarshallingException;
use RuntimeException;

class LearnosityMarshallerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCompleteness()
    {
        $this->markTestSkipped();

        /** @var SchemasService $schemasService */
        $schemasService = AppContainer::getApplicationContainer()->get('schemas_service');
        $factory = new LearnosityMarshallerFactory();
        $exceptionMessages = [];
        foreach ($schemasService->getHtmlSchemas()['allowedHtmlTags'] as $elementName) {
            try {
                $element = new \DOMElement($elementName, 'hello');
                $marshaller = $factory->createMarshaller($element);
                $component = $marshaller->unmarshall($element);
                echo QtiComponentUtil::marshall($component) . PHP_EOL;
            } catch (RuntimeException $e) {
                $exceptionMessages[] = $e->getMessage();
            } catch (UnmarshallingException $e) {
                $exceptionMessages[] = $e->getMessage();
            }
        }
        echo implode(PHP_EOL, $exceptionMessages);
    }
}
