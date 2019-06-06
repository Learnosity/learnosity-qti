<?php

namespace LearnosityQti\Processors\QtiV2\Out\ResponseProcessingBuilders;

use qtism\common\enums\BaseType;
use qtism\data\processing\ResponseProcessing;
use qtism\data\rules\SetOutcomeValue;
use qtism\data\rules\ResponseRuleCollection;
use qtism\data\rules\ResponseRule;
use qtism\data\rules\ResponseCondition;
use qtism\data\rules\ResponseIf;
use qtism\data\rules\ResponseElseIf;
use qtism\data\rules\ResponseElse;
use qtism\data\expressions\operators\Match;
use qtism\data\expressions\Variable;
use qtism\data\expressions\BaseValue;
use qtism\data\expressions\Correct;
use qtism\data\expressions\ExpressionCollection;

class QtiResponseProcessingBuilder
{

    public function build($score)
    {
        // creating feedback outcome
        $feedbackResponseComponent = new SetOutcomeValue('FEEDBACK', new Variable('RESPONSE'));
        $responseRuleCollection = new ResponseRuleCollection();
        $responseRuleCollection->attach($feedbackResponseComponent);

        // creating responseIf condition
        $responseIfexpressionCollection = new ExpressionCollection();
        $responseIfexpressionCollection->attach(new Variable('RESPONSE'));
        $responseIfexpressionCollection->attach(new Correct('RESPONSE'));

        $responseIfComponent = new SetOutcomeValue('SCORE', new BaseValue(BaseType::FLOAT, $score));
        $responseIfRuleCollection = new ResponseRuleCollection();
        $responseIfRuleCollection->attach($responseIfComponent);
        $responseIf = new ResponseIf(new Match($responseIfexpressionCollection), $responseIfRuleCollection);

        // generating response else condition
        $responseElseComponent = new SetOutcomeValue('SCORE', new BaseValue(BaseType::FLOAT, 0));
        $responseElseRuleCollection = new ResponseRuleCollection();
        $responseElseRuleCollection->attach($responseElseComponent);
        $responseElse = new ResponseElse($responseElseRuleCollection);

        // merge response conditions
        $responseCondition = new ResponseCondition($responseIf, null, $responseElse);
        $responseRuleCollection->attach($responseCondition);

        // set response rules to responseProcessing
        $responseProcessing = new ResponseProcessing();
        $responseProcessing->setResponseRules($responseRuleCollection);
        return $responseProcessing;
    }
}
