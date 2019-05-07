<?php

namespace LearnosityQti\Processors\IMSCP\In;

use LearnosityQti\Processors\IMSCP\Entities\Item;
use LearnosityQti\Processors\IMSCP\Entities\Organization;
use qtism\data\storage\xml\Utils as XmlUtils;

class OrganizationsMapper
{
    public function map(array $organisationElements)
    {
        $organisations = [];
        foreach ($organisationElements as $organisationElement) {
            $organisation = new Organization();
            $organisation->setIdentifier(XmlUtils::getDOMElementAttributeAs($organisationElement, 'identifier'));
            $organisation->setStructure(XmlUtils::getDOMElementAttributeAs($organisationElement, 'structure'));

            $titleElements = XmlUtils::getChildElementsByTagName($organisationElement, 'title');
            if (!empty($titleElements)) {
                $organisation->setTitle($titleElements[0]->nodeValue);
            }
            $itemElements = XmlUtils::getChildElementsByTagName($organisationElement, 'item');
            $items = [];
            foreach ($itemElements as $itemElement) {
                $item = new Item();
                $item->setTitle(XmlUtils::getChildElementsByTagName($itemElement, 'title'));
                $item->setIdentifier(XmlUtils::getDOMElementAttributeAs($itemElement, 'identifier'));
                $item->setIdentifierref(XmlUtils::getDOMElementAttributeAs($itemElement, 'identifierref'));
                $item->setIsvisible(XmlUtils::getDOMElementAttributeAs($itemElement, 'isvisible'));
                $items[] = $item;
            }
            $organisation->setItems($items);
            $organisations[] = $organisation;
        }
        return $organisations;
    }
}
