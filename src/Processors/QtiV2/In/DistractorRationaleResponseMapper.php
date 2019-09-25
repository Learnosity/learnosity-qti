<?php
namespace LearnosityQti\Processors\QtiV2\In;

use DOMDocument;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Utils\General\DOMHelper;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\RubricBlock;


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
            $dom = DOMHelper::getDomForXml($xml);
            $innerContent = DOMHelper::getInnerXmlFragmentFromDom($dom);
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
}
