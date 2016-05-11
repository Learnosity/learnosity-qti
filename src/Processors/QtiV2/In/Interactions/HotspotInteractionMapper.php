<?php

namespace LearnosityQti\Processors\QtiV2\In\Interactions;

use LearnosityQti\Entities\QuestionTypes\hotspot;
use LearnosityQti\Entities\QuestionTypes\hotspot_area_attributes;
use LearnosityQti\Entities\QuestionTypes\hotspot_area_attributes_global;
use LearnosityQti\Entities\QuestionTypes\hotspot_image;
use LearnosityQti\Processors\QtiV2\In\Validation\HotspotInteractionValidationBuilder;
use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\common\datatypes\Coords;
use qtism\common\datatypes\Shape;
use qtism\data\content\interactions\HotspotChoice;
use qtism\data\content\interactions\HotspotChoiceCollection;
use qtism\data\content\interactions\HotspotInteraction;
use qtism\data\content\interactions\Prompt;
use qtism\data\content\xhtml\Object;

class HotspotInteractionMapper extends AbstractInteractionMapper
{
    public function getQuestionType()
    {
        /** @var HotspotInteraction $interaction */
        $interaction = $this->interaction;
        $hotspot = new hotspot('hotspot');

        // Slab the areas
        $imageObject = $interaction->getObject();
        $hotspot->set_image($this->buildHotspotImage($imageObject));

        // Support mapping for <prompt>
        if ($interaction->getPrompt() instanceof Prompt) {
            $promptContent = $interaction->getPrompt()->getContent();
            $hotspot->set_stimulus(QtiMarshallerUtil::marshallCollection($promptContent));
        }

        // Map the hotspot areas
        $areas = $this->buildAreas($interaction->getHotspotChoices(), $imageObject);
        $hotspot->set_areas($areas);

        // Setup the area attributes with assumption
        // TODO: Let's say the default fill is always clear, and the stroke would be blackish
        $globalAttributes = new hotspot_area_attributes_global();
        $globalAttributes->set_fill("rgba(0,0,0,0)");
        $globalAttributes->set_stroke("rgba(25, 90, 107, 0.5)");
        $areaAttributes = new hotspot_area_attributes();
        $areaAttributes->set_global($globalAttributes);
        $hotspot->set_area_attributes($areaAttributes);

        // Partial support for @maxChoices
        // @maxChoices of 0 or more than 1 would then would set `multiple_responses` to true
        $maxChoices = $interaction->getMaxChoices();
        if ($maxChoices !== 1) {
            if ($maxChoices !== 0 && $maxChoices !== count($areas)) {
                // We do not support specifying amount of areas
                LogService::log(
                    "Allowing multiple responses of max " . count($areas) . " options, however " .
                    "maxChoices of $maxChoices would be ignored since we can't support exact number"
                );
            }
            $hotspot->set_multiple_responses(true);
        }

        // Ignoring @minChoices
        if (!empty($interaction->getMinChoices())) {
            LogService::log('Attribute minChoices is not supported. Thus, ignored');
        }

        // Build validation
        $validationBuilder = new HotspotInteractionValidationBuilder(
            $this->responseDeclaration,
            $areas,
            $maxChoices
        );
        $validation = $validationBuilder->buildValidation($this->responseProcessingTemplate);
        if (!empty($validation)) {
            $hotspot->set_validation($validation);
        }
        return $hotspot;
    }

    private function buildHotspotImage(Object $imageObject)
    {
        $image = new hotspot_image();
        $image->set_source($imageObject->getData());
        $imageHeight = $imageObject->getHeight();
        if ($imageHeight > 0) {
            $image->set_height($imageHeight);
        }
        $imageWidth = $imageObject->getWidth();
        if ($imageWidth > 0) {
            $image->set_width($imageWidth);
        }
        return $image;
    }

    private function buildAreas(HotspotChoiceCollection $hotspotChoices, Object $imageObject)
    {
        /* @var $choice HotspotChoice */
        $areas             = [];
        $unsupportedShapes = [];
        foreach ($hotspotChoices as $key => $choice) {
            // Store unsupported shapes so later on we can inform that we are not supporting them
            $shape = $choice->getShape();
            if ($shape != Shape::RECT || $choice->getClass() != Shape::POLY) {
                if (in_array($shape, $unsupportedShapes)) {
                    $unsupportedShapes[] = $shape;
                }
            }
            // Store its identifier key for validation purposes
            $areas[$choice->getIdentifier()] = $this->transformCoordinates($choice->getCoords(), $choice->getShape(), $imageObject);
        }
        return $areas;
    }

    private function transformCoordinates(Coords $coords, $shape, Object $imageObject)
    {
        // Yes, width and height is necessary unfortunately
        $width  = $imageObject->getWidth();
        $height = $imageObject->getHeight();

        $coords = explode(',', $coords);
        switch ($shape) {
            case Shape::RECT:
                $leftX   = round($coords[0] / $width * 100, 2);
                $topY    = round($coords[1] / $height * 100, 2);
                $rightX  = round($coords[2] / $width * 100, 2);
                $bottomY = round($coords[3] / $height * 100, 2);
                $result = [
                    ['x' => $leftX, 'y' => $topY], // Top left
                    ['x' => $rightX, 'y' => $topY], // Top right
                    ['x' => $rightX, 'y' => $bottomY], // Bottom right
                    ['x' => $leftX, 'y' => $bottomY], // Bottom left
                ];
                return $result;
            default:
                return null;
        }
    }
}
