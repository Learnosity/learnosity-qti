<?php

namespace LearnosityQti\Processors\QtiV2\Out\ResponseProcessing;

use qtism\common\enums\BaseType;
use qtism\data\expressions\BaseValue;
use qtism\data\expressions\Correct;
use qtism\data\expressions\ExpressionCollection;
use qtism\data\expressions\operators\Match;
use qtism\data\expressions\operators\Multiple;
use qtism\data\expressions\Variable;
use qtism\data\processing\ResponseProcessing;
use qtism\data\rules\ResponseCondition;
use qtism\data\rules\ResponseElse;
use qtism\data\rules\ResponseIf;
use qtism\data\rules\ResponseRuleCollection;
use qtism\data\rules\SetOutcomeValue;

class QtiResponseProcessingBuilder
{

    public function build($score)
    {
        $responseRuleCollection = new ResponseRuleCollection();

        // creating feedback outcome
        $multipleExpression = new ExpressionCollection();
        $variable = new Variable('RESPONSE');
        $multipleExpression->attach($variable);
        $feedbackResponseComponent = new SetOutcomeValue('FEEDBACK', new Multiple($multipleExpression));
        $responseRuleCollection->attach($feedbackResponseComponent);
        
        // creating responseIf condition
        $responseIfExpressionCollection = new ExpressionCollection();
        $responseIfExpressionCollection->attach(new Variable('RESPONSE'));
        $responseIfExpressionCollection->attach(new Correct('RESPONSE'));

        $responseIfComponent = new SetOutcomeValue('SCORE', new BaseValue(BaseType::FLOAT, $score));
        $responseIfRuleCollection = new ResponseRuleCollection();
        $responseIfRuleCollection->attach($responseIfComponent);
        $responseIf = new ResponseIf(new Match($responseIfExpressionCollection), $responseIfRuleCollection);

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
