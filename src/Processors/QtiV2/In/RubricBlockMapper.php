<?php

namespace LearnosityQti\Processors\QtiV2\In;

use DOMDocument;
use DOMElement;
use DOMXPath;
use LearnosityQti\Entities\Question;
use LearnosityQti\Entities\QuestionTypes\rating;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\In\DistractorRationaleResponseMapper;
use LearnosityQti\Processors\QtiV2\In\SharedPassageMapper;
use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\General\DOMHelper;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Utils\UuidUtil;
use qtism\data\content\RubricBlock;
use qtism\data\storage\xml\XmlDocument;
use qtism\data\View;
use qtism\data\ViewCollection;
use function ctype_digit;

class RubricBlockMapper
{
    protected $useRatingQuestion = true;
    protected $shouldParseRubricTableContent = false;
    protected $useStrictRubricLabelOrder = false;

    private $sourceDirectoryPath;
    private $rubricPointValue;

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
    public function parseWithRubricBlockComponent(RubricBlock $rubricBlock, $foundScoringGuidance = false)
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

            case ($rubricBlock->getUse() === 'rationale'):
                if ($views->contains(View::CANDIDATE)) {
                    $mapper = new DistractorRationaleResponseMapper();
                    $distractorRationalResponseLevelArray = $mapper->parseWithDistractorRationaleResponseComponent($rubricBlock);
                    $result ['question_metadata'] = $distractorRationalResponseLevelArray;
                } else if ($views->contains(View::AUTHOR) || $views->contains(View::SCORER) || $views->contains(View::TUTOR)) {
                    $mapper = new DistractorRationaleResponseMapper();
                    $distractorRationalResponseLevelArray = $mapper->parseWithDistractorRationalePerResponseComponent($rubricBlock);
                    $result ['question_metadata'] = $distractorRationalResponseLevelArray;
                }
                break;

            case ($rubricBlock->getClass() === 'DistractorRationale');
                if ($views->contains(View::AUTHOR)) {
                    $result = [
                        'question_metadata' => [
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

            case ($rubricBlock->getUse() === 'stimulus'):
                if ($views->contains(View::CANDIDATE)) {
                    $contents = QtiMarshallerUtil::marshallCollection($rubricBlock->getContent());
                    $result = [
                        'stimulus' => $contents,
                    ];
                }
                break;
            case ($rubricBlock->getClass() === 'ScoringGuidance'):
                /* falls through */
            case (!$views->contains(View::CANDIDATE)):
                // Treat as author/scorer rubric content
                $result = $this->parseRubricContentWithRubricBlockComponent($rubricBlock, $foundScoringGuidance);
                break;
        }

        if (!empty($result)) {
            $result['views'] = $rubricBlock->getViews();
            return $result;
        } else {
            $rubricUse = $rubricBlock->getUse();
            $rubricClass = $rubricBlock->getClass();
            throw new MappingException("Could not map <rubricBlock> with use: '{$rubricUse}', class: '{$rubricClass}'");
        }
    }

    public function setRubricPointValue($rubricPointValue)
    {
        $this->rubricPointValue = $rubricPointValue;
    }

    /**
     * Deserialize an XML string into a QTI-validated document.
     *
     * @param  string $xmlString - the XML content to deserialize
     * @param  boolean $validateXml - whether to validate the XML
     *                              before attempting to deserialize it
     *
     * @return XmlDocument
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
     * @param  XmlDocument $xmlDocument
     *
     * @return RubricBlock
     *
     * @throws MappingException
     */
    private function getRubricBlockFromXmlDocument(XmlDocument $xmlDocument)
    {
        $rubricBlock = $xmlDocument->getDocumentComponent();
        if (!($rubricBlock instanceof RubricBlock)) {
            throw new MappingException('XML is not a valid <rubricBlock>');
        }

        return $rubricBlock;
    }

    private function parseRubricTableContentFromXmlUsingXPath(DOMXPath $xpath)
    {
        $aliasColumns = [
            'score' => 'value',
            'level' => 'value',
        ];

        // Extract the rows from the content
        $rows = $xpath->query('//tr');
        $headerRows = $xpath->query('//thead/tr');

        // Use topmost body row as header if there is no dedicated header row
        $useHeaderInBodyRows = ($headerRows->length === 0);

        /** @var DOMElement $headerRow */
        if ($useHeaderInBodyRows) {
            $headerRow = $rows->item(0);
        } else {
            $headerRow = $headerRows->item(0);
        }

        // Obtain the raw header values from the header row nodes
        $headers = [];
        foreach ($headerRow->childNodes as $headerCell) {
            $headers[] = strtolower(trim($headerCell->textContent));
        }

        // Sanitize alias headers
        foreach ($headers as $index => $header) {
            if (isset($aliasColumns[$header])) {
                $headers[$index] = $aliasColumns[$header];
            }
        }

        return [$rows, $headers, $useHeaderInBodyRows];
    }

    /**
     * We determine that a rubric block is a rating question by the following rule
     *   - useRating AND ((useTable AND isTable) OR (isFirst AND hasPointValue))
     */
    private function parseRubricContentWithRubricBlockComponent(RubricBlock $rubricBlock, $foundScoringGuidance)
    {
        $isFirst = !$foundScoringGuidance;

        $rows = null;
        $headers = [];
        $useHeaderInBodyRows = false;
        $rubricPointValue = null;
        $result = [
            'features'  => [],
            'questions' => [],
        ];

        // TODO: Implement creation of all the rubric questions/features
        // HACK: HACK HACK HACK HACK HACK HACK HACK HACK
        // Try to parse the rubric content to create an interactable widget for it
        try {
            if (!$this->useRatingQuestion) {
                throw new MappingException('rating question type mapping is disabled');
            }

            // Prepare the DOM for reading
            $xml = QtiMarshallerUtil::marshall($rubricBlock);

            // Prevent/handle XML parse errors (from bad XML input)
            $xml = DOMHelper::sanitizeXml($xml);
            $dom = DOMHelper::getDomForXml($xml);
            $xpath = new DOMXPath($dom);

            // Prepare inner rubric content
            $innerContentNode = DOMHelper::getInnerXmlFragmentFromDom($dom);
            // XXX: The following needs to be done in this order. Need to figure out why
            $innerHTML = $dom->saveXML($innerContentNode);
            $dom->replaceChild($innerContentNode, $dom->documentElement);

            // Make sure we have valid parseable table
            $hasRubricTableContent = $xpath->query('//table')->length;
            if ($this->shouldParseRubricTableContent && $hasRubricTableContent) {
                list($rows, $headers, $useHeaderInBodyRows) = $this->parseRubricTableContentFromXmlUsingXPath($xpath);
            } elseif ($isFirst && $this->useRubricPointValueForRubric($rubricBlock)) {
                $rubricPointValue = $this->rubricPointValue;
            } else {
                throw new MappingException('invalid or unrecognized format in scoring guidance');
            }

            try {
                $ratingQuestion = $this->buildRatingQuestion($headers, $rows, $useHeaderInBodyRows, $innerHTML, $rubricPointValue);
            } catch (MappingException $e) {
                // HACK: Add some specific logging for failures with building the rating question type
                LogService::log('<rubricBlock> - Failed to build rating question for 1 or more ScoringGuidance rubrics');
                throw $e;
            }

            $result['questions'][$ratingQuestion->get_reference()] = $ratingQuestion;

        } catch (MappingException $e) {
            // NOTE: Instead of an exception, we can create a plain shared passage for the content as a fallback.
            // throw new MappingException('Could not map <rubricBlock> with class: \'ScoringGuidance\' - '.$e->getMessage(), $e);

            // Fall back to a shared passage with the rubric content in it
            $mapper = new SharedPassageMapper($this->sourceDirectoryPath);
            $result = $mapper->parseWithRubricBlockComponent($rubricBlock);
        }

        // NOTE: It must be flagged in the result that this is scoring
        // rubric content, as opposed to regular item or passage content
        $result['type']  = 'ScoringGuidance';
        $result['label'] = $rubricBlock->getLabel();
        return $result;
    }

    private function buildRatingQuestion($headers, $rows, $useHeaderInBodyRows, $innerHTML = null, $defaultPointValue = null)
    {
        // Validate for minimum processable headers
        static $requiredColumns = [
            'value',
            'description',
        ];

        $ratingOptions = [];

        if (isset($headers, $rows) && empty(array_diff($requiredColumns, $headers))) {
            // Prepare the rating options using the table information
            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex === 0 && $useHeaderInBodyRows) {
                    continue;
                }
                foreach ($row->childNodes as $cellIndex => $cell) {
                    // HACK: filter out rows that the rating question type won't understand
                    switch (true) {
                        // Assume value must be int
                        case $headers[$cellIndex] === 'value' && !ctype_digit(trim($cell->textContent)):
                            continue 3;
                            // FIXME: If value isn't the first column, this still inserts a row. Need to prevent that

                        default:
                            break;
                    }

                    // TODO: Consider supporting HTML content in `description` column
                    // For that, we would need a mini-schema based on `column name` (mapped to the header name) -> type
                    $ratingOptions[$rowIndex][$headers[$cellIndex]] = $cell->textContent;
                }

                // If no `label`, set `label` to `value`
                if (!isset($ratingOptions[$rowIndex]['label'])) {
                    $ratingOptions[$rowIndex]['label'] = $ratingOptions[$rowIndex]['value'];
                }
            }
        } elseif (isset($defaultPointValue)) {
            // Prepare the rating options using the default point value
            for ($n = 1; $n <= $defaultPointValue; $n++) {
                $ratingOptions[$n]['value']       = $n;
                $ratingOptions[$n]['description'] = $n;
                $ratingOptions[$n]['label']       = $n;
            }
        } else {
            throw new MappingException('missing required columns in scoring guidance');
        }

        // HACK: Sort rating options by value
        usort($ratingOptions, function($a, $b) {
            return strcmp($a['value'], $b['value']);
        });

        // Create a rating question type
        // FIXME: Need to deal with random reference problem here too (see shared passages)
        $rating = new rating('rating', $ratingOptions);
        $rating->set_stimulus($innerHTML);
        $ratingQuestion = new Question('rating', UuidUtil::generate(), $rating);

        return $ratingQuestion;
    }

    private function useRubricPointValueForRubric(RubricBlock $rubricBlock)
    {
        // FIXME: Need to figure out how to generalize this, so we don't have to depend on a label convention
        return isset($this->rubricPointValue) && (!$this->useStrictRubricLabelOrder || $rubricBlock->getLabel() === '1');
    }
}
