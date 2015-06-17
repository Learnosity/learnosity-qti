<?php

namespace Learnosity\Mappers\QtiV2\Import;

use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiV2Util;
use Learnosity\Utils\FileSystemUtil;
use qtism\data\storage\xml\XmlCompactDocument;
use qtism\data\storage\xml\XmlStorageException;

class TestMapper
{
    public function parse($xmlString)
    {
        try {
            $document = new XmlCompactDocument();
            $document->loadFromString($xmlString);

            //get all assessmentItemRef
            $assessmentTest = $document->getDocumentComponent();
            $assessmentItemRefs = $assessmentTest->getComponentsByClassName('assessmentItemRef', true);
            die;
        } catch (XmlStorageException $e) {
            $previousException = $e->getPrevious();
            $msg = $e->getMessage(). "\n". $previousException->getMessage();
            throw new MappingException($msg, MappingException::CRITICAL, $previousException);
        } catch (\Exception $e) {
            throw $e;
        }
        die;
    }
} 
