<?php

namespace Learnosity\Processors\QtiV2\Out\Processings;

interface ProcessingInterface
{
    public function processQuestions(array $questions);
    public function processItemContent($content);
}
