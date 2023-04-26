<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\In\Interactions;

use LearnosityQti\AppContainer;
use LearnosityQti\Entities\Item\item;
use LearnosityQti\Entities\Question;
use LearnosityQti\Entities\QuestionTypes\clozedropdown;
use LearnosityQti\Entities\QuestionTypes\clozedropdown_validation;
use LearnosityQti\Entities\QuestionTypes\clozedropdown_validation_valid_response;
use LearnosityQti\Entities\QuestionTypes\clozetext;
use LearnosityQti\Entities\QuestionTypes\clozetext_validation;
use LearnosityQti\Entities\QuestionTypes\clozetext_validation_valid_response;
use LearnosityQti\Processors\QtiV2\In\ItemMapper;
use LearnosityQti\Tests\AbstractTest;

class TextEntryInlineChoiceInteractionTest extends AbstractTest
{
    private $file;
    /* @var $mapper ItemMapper */
    private $mapper;

    public function setup()
    {
        $this->file = $this->getFixtureFileContents('interactions/textEntryInteraction-inlineChoiceInteraction.xml');
        $this->mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
    }

    public function validateGeneric($data)
    {
        /** @var item $item */
        $item = $data[0];
        $this->assertInstanceOf('LearnosityQti\Entities\Item\item', $item);
        $this->assertEquals('res_AA-FIB_B13_CH1_geoc_f1f1', $item->get_reference());
        $this->assertContains(
            '<span class="learnosity-response question-res_AA-FIB_B13_CH1_geoc_f1f1_RESPONSE"></span>',
            $item->get_content()
        );
        $this->assertContains(
            '<span class="learnosity-response question-res_AA-FIB_B13_CH1_geoc_f1f1_RESPONSE2"></span>',
            $item->get_content()
        );
        $this->assertEquals('published', $item->get_status());
        $this->assertEquals('AA-FIB_B13_CH1_geoc_f1f1', $item->get_description());
        $this->assertCount(2, $item->get_questionReferences());
        $this->assertContains('res_AA-FIB_B13_CH1_geoc_f1f1_RESPONSE', $item->get_questionReferences());
        $this->assertContains('res_AA-FIB_B13_CH1_geoc_f1f1_RESPONSE2', $item->get_questionReferences());
    }

    public function validateInlineChoiceInteraction($data)
    {
        /** @var Question $question */
        $question = $data[1][1];
        $this->assertInstanceOf('LearnosityQti\Entities\Question', $question);
        $this->assertEquals('clozedropdown', $question->get_type());

        /** @var clozedropdown $q */
        $q = $question->get_data();
        $this->assertInstanceOf('LearnosityQti\Entities\QuestionTypes\clozedropdown', $q);
        $this->assertEquals('clozedropdown', $q->get_type());
        $this->assertEquals('{{response}}', $q->get_template());

        /** @var clozedropdown_validation $validation */
        $validation = $q->get_validation();
        $this->assertInstanceOf('LearnosityQti\Entities\QuestionTypes\clozedropdown_validation', $validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());
        $this->assertNull($validation->get_alt_responses());

        /** @var clozedropdown_validation_valid_response $validResponse */
        $validResponse = $validation->get_valid_response();
        $this->assertInstanceOf(
            'LearnosityQti\Entities\QuestionTypes\clozedropdown_validation_valid_response',
            $validResponse
        );
        $this->assertEquals(1, $validResponse->get_score());
        $this->assertEquals(['York'], $validResponse->get_value());
    }

    public function validateTextEntryInteraction($data)
    {
        /** @var Question $question */
        $question = $data[1][0];
        $this->assertInstanceOf('LearnosityQti\Entities\Question', $question);
        $this->assertEquals('res_AA-FIB_B13_CH1_geoc_f1f1_RESPONSE', $question->get_reference());
        $this->assertEquals('clozetext', $question->get_type());

        /** @var clozetext $q */
        $q = $question->get_data();
        $this->assertInstanceOf('LearnosityQti\Entities\QuestionTypes\clozetext', $q);
        $this->assertEquals('clozetext', $q->get_type());
        $this->assertEquals('{{response}}', $q->get_template());
        $this->assertEquals(15, $q->get_max_length());

        /** @var clozetext_validation $validation */
        $validation = $q->get_validation();
        $this->assertInstanceOf('LearnosityQti\Entities\QuestionTypes\clozetext_validation', $validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());
        $this->assertNull($validation->get_alt_responses());

        /** @var clozetext_validation_valid_response $validResponse */
        $validResponse = $validation->get_valid_response();
        $this->assertInstanceOf(
            'LearnosityQti\Entities\QuestionTypes\clozetext_validation_valid_response',
            $validResponse
        );
        $this->assertEquals(1, $validResponse->get_score());
        $this->assertEquals(['York'], $validResponse->get_value());
    }

    public function testDualInteractions()
    {
        $data = $this->mapper->parse($this->file);
        $this->assertCount(3, $data);
        $this->assertCount(2, $data[1]);
        $this->validateGeneric($data);
        $this->validateInlineChoiceInteraction($data);
        $this->validateTextEntryInteraction($data);
    }
}
