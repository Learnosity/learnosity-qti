<?php

namespace LearnosityQti\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Entities\QuestionTypes\imageclozeassociation;
use LearnosityQti\Entities\QuestionTypes\imageclozeassociation_image;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\Out\Validation\ImageclozeassociationValidationBuilder;
use LearnosityQti\Utils\CurlUtil;
use LearnosityQti\Utils\MimeUtil;
use LearnosityQti\Utils\QtiCoordinateUtil;
use LearnosityQti\Utils\SimpleHtmlDom\SimpleHtmlDom;
use qtism\common\datatypes\QtiShape;
use qtism\data\content\interactions\AssociableHotspot;
use qtism\data\content\interactions\AssociableHotspotCollection;
use qtism\data\content\interactions\GapImg;
use qtism\data\content\interactions\GapImgCollection;
use qtism\data\content\interactions\GraphicGapMatchInteraction;
use qtism\data\content\ObjectFlowCollection;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\ObjectElement;

class ImageclozeassociationMapper extends AbstractQuestionTypeMapper
{
    const GAPIMG_IDENTIFIER_PREFIX = 'CHOICE_';
    const ASSOCIABLEHOTSPOT_IDENTIFIER_PREFIX = 'ASSOCIABLEHOTSPOT_';

    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        //TODO: Need validation a question shall have at least 1 {{response}} and 1 item in `possible_responses`
        /** @var imageclozeassociation $question */
        $question = $questionType;

        // Map `possible_responses` to `gapImg`(s)
        $possibleResponses = $question->get_possible_responses();
        $matchMax = !is_null($question->get_duplicate_responses()) ? count($possibleResponses) : 1;
        $gapImageCollection = $this->buildGapImgCollection($possibleResponses, $matchMax);

        // Build the main background image `object`
        $imageObject = $this->buildMainImageObject($question->get_image());

        // Build associable hotspots based on `response_positions`
        $associableHotspotCollection = $this->buildAssociableHotspotCollection($question->get_response_positions(), $imageObject->getWidth(), $imageObject->getHeight());

        // Build dah` interaction
        $interaction = new GraphicGapMatchInteraction($interactionIdentifier, $imageObject, $gapImageCollection, $associableHotspotCollection);
        $interaction->setLabel($interactionLabel);
        $interaction->setPrompt($this->convertStimulusForPrompt($question->get_stimulus()));

        $validationBuilder = new ImageclozeassociationValidationBuilder($possibleResponses);
        list($responseDeclaration, $responseProcessing) = $validationBuilder->buildValidation($interaction->getResponseIdentifier(), $question->get_validation());

        return [$interaction, $responseDeclaration, $responseProcessing];
    }

    private function buildAssociableHotspotCollection(array $responsePositions, $imageObjectWidth, $imageObjectHeight)
    {
        $associableHotspotCollection = new AssociableHotspotCollection();
        foreach ($responsePositions as $index => $responsePosition) {
            // Let's just make an assumption these images will have width of 100px and height of 20px
            $rectangleWidth = 100;
            $rectangleHeight = 20;
            $coords = QtiCoordinateUtil::convertPercentageToQtiCoords(
                $responsePosition,
                $imageObjectWidth,
                $imageObjectHeight,
                $rectangleWidth,
                $rectangleHeight
            );
            $associableHotspot = new AssociableHotspot(self::ASSOCIABLEHOTSPOT_IDENTIFIER_PREFIX . $index, 1, QtiShape::RECT, $coords);
            $associableHotspotCollection->attach($associableHotspot);
        }
        return $associableHotspotCollection;
    }

    private function buildGapImgCollection(array $possibleResponses, $matchMax)
    {
        $gapImageCollection = new GapImgCollection();
        foreach ($possibleResponses as $index => $possibleResponse) {
            $html = new SimpleHtmlDom();
            $html->load($possibleResponse);
            $img = $html->find('img');
            // Detect `img` and make sure it is an image
            if (count($img) === 1) {
                // TODO: Validation these attributes exists
                $src = $img[0]->src;
                $imagesize = getimagesize(CurlUtil::prepareUrlForCurl($src));
                $gapImageObject = new Object($src, $imagesize['mime']);
                $gapImageObject->setWidth($imagesize[0]);
                $gapImageObject->setHeight($imagesize[1]);
                // No `img` assuming its all text
            } elseif (count($img) === 0) {
                $gapImageObject = $this->convertTextToObjectWithBase64ImageString($possibleResponse);
            } else {
                throw new MappingException('Does not support mapping `possible_responses` as HTML, has to be either just a single `image` or `text`');
            }
            $gapImageCollection->attach(new GapImg(self::GAPIMG_IDENTIFIER_PREFIX . $index, $matchMax, $gapImageObject));
        }
        return $gapImageCollection;
    }

    private function buildMainImageObject(imageclozeassociation_image $image)
    {
        $imageSrc = $image->get_src();
        list($imageWidth, $imageHeight) = CurlUtil::getImageSize(CurlUtil::prepareUrlForCurl($imageSrc));
        $imageObject = new Object($imageSrc, MimeUtil::guessMimeType($imageSrc));
        $imageObject->setWidth($imageWidth);
        $imageObject->setHeight($imageHeight);

        // Map `alt` to object content
        if (!is_null($image->get_alt())) {
            $objectFlowCollection = new ObjectFlowCollection();
            $objectFlowCollection->attach(new TextRun($image->get_alt()));
            $imageObject->setContent($objectFlowCollection);
        }
        return $imageObject;
    }

    private function convertTextToObjectWithBase64ImageString($text)
    {
        $string = $text;
        $font = 3;
        $width  = ImageFontWidth($font) * strlen($string);
        $height = ImageFontHeight($font);

        ob_start();
        $im = @imagecreate ($width, $height);
        $background_color = imagecolorallocate($im, 255, 255, 255); // White background
        $text_color = imagecolorallocate ($im, 0, 0, 0); // Black text
        imagestring($im, $font, 0, 0,  $string, $text_color);
        imagepng($im);
        $imagedata = ob_get_contents();
        ob_end_clean();

        $imagedata = 'data:image/png;base64,' . base64_encode($imagedata);

        $gapImageObject = new Object($imagedata, 'image/png');
        $gapImageObject->setWidth($width);
        $gapImageObject->setHeight($height);
        return $gapImageObject;
    }
}
