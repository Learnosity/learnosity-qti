<?php

namespace LearnosityQti\Processors\QtiV2\In;

use qtism\data\content\RubricBlock;
use qtism\data\storage\xml\XmlDocument;
use qtism\data\View;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\In\SharedPassageMapper;
use LearnosityQti\Utils\QtiMarshallerUtil;

class RubricBlockMapper
{
    private $sourceDirectoryPath;

    public function __construct($sourceDirectoryPath = null)
    {
        $this->sourceDirectoryPath = $sourceDirectoryPath;
    }

    /**
     * Parse an XML string representing a QTI-formatted rubric block
     * component and generate appropriate Learnosity-compatible content.
     *
     * @param  string  $xmlString
     * @param  boolean $validateXml
     *
     * @return mixed
     */
    public function parse($xmlString, $validateXml = true)
    {
        /** @var XmlDocument $xmlDocument */
        $xmlDocument = $this->deserializeXml($xmlString, $validateXml);
        /** @var RubricBlock $rubricBlock */
        $rubricBlock = $this->getRubricBlockFromXmlDocument($xmlDocument);

        return $this->parseWithRubricBlockComponent($rubricBlock);
    }

    /**
     * Parse a QTI rubric block component and generate appropriate
     * Learnosity-compatible content.
     *
     * @param  RubricBlock $rubricBlock
     *
     * @return mixed
     */
    public function parseWithRubricBlockComponent(RubricBlock $rubricBlock)
    {
        $result = null;
        /** @var ViewCollection $views */
        $views = $rubricBlock->getViews();
        // Get the correct mapper for the <rubricBlock>
        switch (true) {
            case ($rubricBlock->getUse() === 'context'):
                if ($views->contains(View::CANDIDATE)) {
                    $mapper = new SharedPassageMapper($this->sourceDirectoryPath);
                    $result = $mapper->parseWithRubricBlockComponent($rubricBlock);
                }
                break;

            case ($rubricBlock->getUse() === 'stimulus'):
                if ($views->contains(View::CANDIDATE)) {
                    $contents = QtiMarshallerUtil::marshallCollection($rubricBlock->getContent());
                    $result = [
                        'stimulus' => $contents,
                    ];
                }
                break;

            case ($rubricBlock->getClass() === 'DistractorRationale'):
                if ($views->contains(View::AUTHOR)) {
                    $result = [
                        'metadata' => [
                            'distractor_rationale_author' => [
                                [
                                    'label' => $rubricBlock->getLabel(),
                                    'content' => QtiMarshallerUtil::marshallCollection($rubricBlock->getContent()),
                                ]
                            ],
                        ],
                    ];
                }
                break;
        }

        if (isset($result)) {
            return $result;
        } else {
            // FIXME: Should this throw a mapping exception or just be ignored?
            $use = $rubricBlock->getUse();
            throw new MappingException("Could not map <rubricBlock> with use: {$use}");
        }
    }

    /**
     * Deserialize an XML string into a QTI-validated document.
     *
     * @param  string $xmlString - the XML content to deserialize
     * @param  boolean $validateXml - whether to validate the XML
     *                              before attempting to deserialize it
     *
     * @return \qtism\data\storage\xml\XmlDocument
     */
    protected function deserializeXml($xmlString, $validateXml)
    {
        $xmlDocument = new XmlDocument();
        if (!$validateXml) {
            LogService::log('QTI pre-validation is turned off, some invalid attributes might be stripped from XML content upon conversion');
        }
        $xmlDocument->loadFromString($xmlString, $validateXml);

        return $xmlDocument;
    }

    /**
     * Retrieves the rubric block from a given XML document.
     *
     * The rubric block must be the root element of the document.
     *
     * @param  \qtism\data\storage\xml\XmlDocument $xmlDocument
     *
     * @return \qtism\data\content\RubricBlock
     *
     * @throws \LearnosityQti\Exceptions\MappingException
     */
    private function getRubricBlockFromXmlDocument(XmlDocument $xmlDocument)
    {
        $rubricBlock = $xmlDocument->getDocumentComponent();
        if (!($rubricBlock instanceof RubricBlock)) {
            throw new MappingException('XML is not a valid <rubricBlock>');
        }

        return $rubricBlock;
    }
}
