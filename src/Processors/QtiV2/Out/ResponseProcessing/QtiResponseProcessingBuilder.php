<?php
namespace LearnosityQti\Processors\QtiV2\Out\ResponseProcessing;

use qtism\common\enums\BaseType;
use qtism\data\expressions\BaseValue;
use qtism\data\expressions\Correct;
use qtism\data\expressions\ExpressionCollection;
use qtism\data\expressions\operators\IsNull;
use qtism\data\expressions\operators\Match;
use qtism\data\expressions\operators\AndOperator;
use qtism\data\expressions\operators\Multiple;
use qtism\data\expressions\operators\Sum;
use qtism\data\expressions\Variable;
use qtism\data\processing\ResponseProcessing;
use qtism\data\rules\ResponseCondition;
use qtism\data\rules\ResponseElse;
use qtism\data\rules\ResponseIf;
use qtism\data\rules\ResponseRuleCollection;
use qtism\data\rules\SetOutcomeValue;

class QtiResponseProcessingBuilder
{

    public function build($score, $maxscore, $penalty, $feedBackOptions = array(), $type = array())
    {

        $responseRuleCollection = new ResponseRuleCollection();

        // creating feedbackInline outcome for questions which supports feedbackInline
        if (sizeof($feedBackOptions) > 1) {
            $multipleExpression = new ExpressionCollection();
            $variable = new Variable('RESPONSE');
            $multipleExpression->attach($variable);
            $feedbackResponseComponent = new SetOutcomeValue('FEEDBACK', new Multiple($multipleExpression));
            $responseRuleCollection->attach($feedbackResponseComponent);
        }

        $responseIfExpressionCollection = new ExpressionCollection();
        $responseIfExpressionCollection->attach(new Variable('RESPONSE'));

        $responseIfRuleCollection = new ResponseRuleCollection();
        $responseIfNullScoreComponent = new SetOutcomeValue('SCORE', new BaseValue(BaseType::FLOAT, 0.0));
        $responseIfRuleCollection->attach($responseIfNullScoreComponent);

        // generating response else condition
        $responseElseRuleCollection = new ResponseRuleCollection();

        $responseIf = new ResponseIf(new IsNull($responseIfExpressionCollection), $responseIfRuleCollection);
        $responseElse = new ResponseElse($responseElseRuleCollection);

        $responseCondition = new ResponseCondition($responseIf, null, $responseElse);

        // responseCondition for responseElse starts here
        // creating responseIf condition
        $responseIfExpressionCollection1 = new ExpressionCollection();
        $responseIfExpressionCollection1->attach(new Variable('RESPONSE'));
        $responseIfExpressionCollection1->attach(new Correct('RESPONSE'));

        $responseIfRuleCollection1 = new ResponseRuleCollection();

        if (in_array('maxscore', $type)) {
            $responseIfScoreComponent1 = new SetOutcomeValue('SCORE', new Variable('MAXSCORE'));
        } else {
            $responseIfScoreComponent1 = new SetOutcomeValue('SCORE', new BaseValue(BaseType::FLOAT, $score));
        }
        $responseIfRuleCollection1->attach($responseIfScoreComponent1);

        // generating response else condition
        $responseElseRuleCollection1 = new ResponseRuleCollection();
        if (in_array('penalty', $type)) {
            $responseElseComponent1 = new SetOutcomeValue('SCORE', new BaseValue(BaseType::FLOAT, 0 - $penalty));
        } else {
            $responseElseComponent1 = new SetOutcomeValue('SCORE', new BaseValue(BaseType::FLOAT, 0));
        }
        $responseElseRuleCollection1->attach($responseElseComponent1);

        $responseIf1 = new ResponseIf(new Match($responseIfExpressionCollection1), $responseIfRuleCollection1);
        $responseElse1 = new ResponseElse($responseElseRuleCollection1);

        // merge response conditions
        $responseCondition1 = new ResponseCondition($responseIf1, null, $responseElse1);
        $responseRuleCollection->attach($responseCondition);
        $responseElseRuleCollection->attach($responseCondition1);

        // generate outcome value if distrator_rationale_value is set
        if (is_array($feedBackOptions) && !empty($feedBackOptions['genral_feedback'])) {
            $responseFeedbackComponent = new SetOutcomeValue('FEEDBACK_GENERAL', new BaseValue(BaseType::IDENTIFIER, 'correctOrIncorrect'));
            $responseRuleCollection->attach($responseFeedbackComponent);
        }

        // set response rules to responseProcessing
        $responseProcessing = new ResponseProcessing();
        $responseProcessing->setResponseRules($responseRuleCollection);
        return $responseProcessing;
    }

    public function buildResponseProcessingWithMultipleResponse($score, $maxscore, $penalty, $feedBackOptions = array(), $type = array(), $responseIdentifiers = array())
    {
        $responseRuleCollection = new ResponseRuleCollection();
        $andExpressionCollection = new ExpressionCollection();

        foreach ($responseIdentifiers as $rid) {
            $matchExpressionCollection = new ExpressionCollection();
            $matchExpressionCollection->attach(new Variable($rid));
            $matchExpressionCollection->attach(new Correct($rid));

            $match = new Match($matchExpressionCollection);
            $andExpressionCollection->attach($match);
        }

        $and = new AndOperator($andExpressionCollection);

        if (in_array('maxscore', $type)) {
            $responseIfScoreComponent = new SetOutcomeValue('SCORE', new Variable('MAXSCORE'));
        } else {
            $responseIfScoreComponent = new SetOutcomeValue('SCORE', new BaseValue(BaseType::FLOAT, $score));
        }

        $responseIfRuleCollection = new ResponseRuleCollection();
        $responseIfRuleCollection->attach($responseIfScoreComponent);

        // generating response else condition
        $responseElseRuleCollection = new ResponseRuleCollection();
		
		// creating feedback outcome
        if (sizeof($feedBackOptions) > 1) {
            $multipleExpression = new ExpressionCollection();
            $variable = new Variable($rid);
            $multipleExpression->attach($variable);
            $feedbackResponseComponent = new SetOutcomeValue('FEEDBACK', new Multiple($multipleExpression));
            $responseElseRuleCollection1->attach($feedbackResponseComponent);
        }

        if (in_array('penalty', $type)) {
            $responseElseComponent = new SetOutcomeValue('SCORE', new BaseValue(BaseType::FLOAT, 0 - $penalty));
        } else {
            $responseElseComponent = new SetOutcomeValue('SCORE', new BaseValue(BaseType::FLOAT, 0));
        }

        $responseElseRuleCollection->attach($responseElseComponent);
        $responseIf = new ResponseIf($and, $responseIfRuleCollection);
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
