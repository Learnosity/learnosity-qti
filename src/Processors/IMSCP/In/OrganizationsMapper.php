<?php

namespace LearnosityQti\Processors\IMSCP\In;

use LearnosityQti\Processors\IMSCP\Entities\Item;
use LearnosityQti\Processors\IMSCP\Entities\Organization;
use qtism\data\storage\xml\marshalling\Marshaller;

class OrganizationsMapper
{
    public function map(array $organisationElements)
    {
        $organisations = [];
        foreach ($organisationElements as $organisationElement) {
            $organisation = new Organization();
            $organisation->setIdentifier(Marshaller::getDOMElementAttributeAs($organisationElement, 'identifier'));
            $organisation->setStructure(Marshaller::getDOMElementAttributeAs($organisationElement, 'structure'));

            $titleElements = Marshaller::getChildElementsByTagName($organisationElement, 'title');
            if (!empty($titleElements)) {
                $organisation->setTitle($titleElements[0]->nodeValue);
            }
            $itemElements = Marshaller::getChildElementsByTagName($organisationElement, 'item');
            $items = [];
            foreach ($itemElements as $itemElement) {
                $item = new Item();
                $item->setTitle(Marshaller::getChildElementsByTagName($itemElement, 'title'));
                $item->setIdentifier(Marshaller::getDOMElementAttributeAs($itemElement, 'identifier'));
                $item->setIdentifierref(Marshaller::getDOMElementAttributeAs($itemElement, 'identifierref'));
                $item->setIsvisible(Marshaller::getDOMElementAttributeAs($itemElement, 'isvisible'));
                $items[] = $item;
            }
            $organisation->setItems($items);
            $organisations[] = $organisation;
        }
        return $organisations;
    }
}
