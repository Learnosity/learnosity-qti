<?php
namespace LearnosityQti\Processors\QtiV2\In;


use DOMElement;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\RubricBlock;
use SplFileInfo;
use LearnosityQti\Exceptions\MappingException;

class DistractorRationaleResponseMapper
{

    public function __construct()
    {
        
    }

    public function parseWithDistractorRationaleResponseComponent(RubricBlock $rubricBlock)
    {
        $results = [];

        // Fall back to using all the content in the <rubricBlock> verbatim as a single passage
        $xml = QtiMarshallerUtil::marshall($rubricBlock);
        if (strlen(trim($xml)) > 0) {
            $dom = $this->getDomForXml($xml);
            $innerContent = $this->getInnerXmlFragmentFromDom($dom);
            $distractorRationale = array();
            foreach ($innerContent->childNodes as $child) {
                $distractorRationale[] = $child->ownerDocument->saveXML($child);
            }

            $results['distractor_rationale_response_level'] = $distractorRationale;
        } else {
            throw new MappingException('No content found; cannot create distractor rational response level');
        }
        return $results;
    }
    
    private function getDomForXml($xml)
    {
        $dom = new \DOMDocument();

        $dom->preserveWhiteSpace = false;
        $dom->formatOutput       = false;
        $dom->substituteEntities = false;

        $isValid = $dom->loadXML($xml);

        if (!$isValid) {
            throw new MappingException('Invalid XML; Failed to parse DOM for sharedpassage content');
        }

        return $dom;
    }
    
    private function getInnerXmlFragmentFromDom(\DOMDocument $dom)
    {
        $fragment = $dom->createDocumentFragment();
        $childNodes = $dom->documentElement->childNodes;
        while (($node = $childNodes->item(0))) {
            $node->parentNode->removeChild($node);
            $fragment->appendChild($node);
        }

        return $fragment;
    }
}
