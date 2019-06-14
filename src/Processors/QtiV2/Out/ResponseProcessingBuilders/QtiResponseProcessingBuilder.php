<?php
namespace LearnosityQti\Processors\QtiV2\Out\ResponseProcessingBuilders;

use qtism\common\enums\BaseType;
use qtism\data\expressions\BaseValue;
use qtism\data\expressions\Correct;
use qtism\data\expressions\ExpressionCollection;
use qtism\data\expressions\operators\IsNull;
use qtism\data\expressions\operators\Match;
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
            $variable = new Variable('FEEDBACK');
            $baseValue = new BaseValue(BaseType::IDENTIFIER, 'identifier');
            $multipleExpression->attach($variable);
            $multipleExpression->attach($baseValue);
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
        $sumexpressionCollection = new ExpressionCollection();

        $i = 1;
        foreach ($responseIdentifiers as $rid) {

            $responseIfExpressionCollection = new ExpressionCollection();
            $responseIfExpressionCollection->attach(new Variable($rid));

            $responseIfRuleCollection = new ResponseRuleCollection();
            $responseIfNullScoreComponent = new SetOutcomeValue('SCORE' . $i, new BaseValue(BaseType::FLOAT, 0.0));
            $responseIfRuleCollection->attach($responseIfNullScoreComponent);

            // generating response else condition
            $responseElseRuleCollection = new ResponseRuleCollection();

            $responseIf = new ResponseIf(new IsNull($responseIfExpressionCollection), $responseIfRuleCollection);
            $responseElse = new ResponseElse($responseElseRuleCollection);

            $responseCondition = new ResponseCondition($responseIf, null, $responseElse);

            // responseCondition for responseElse starts here
            // creating responseIf condition
            $responseIfExpressionCollection1 = new ExpressionCollection();
            $responseIfExpressionCollection1->attach(new Variable($rid));
            $responseIfExpressionCollection1->attach(new Correct($rid));

            $responseIfRuleCollection1 = new ResponseRuleCollection();

            if (in_array('maxscore', $type)) {
                $responseIfScoreComponent1 = new SetOutcomeValue('SCORE' . $i, new Variable('MAXSCORE'));
            } else {
                $responseIfScoreComponent1 = new SetOutcomeValue('SCORE' . $i, new BaseValue(BaseType::FLOAT, $score));
            }
            $responseIfRuleCollection1->attach($responseIfScoreComponent1);

            // generating response else condition
            $responseElseRuleCollection1 = new ResponseRuleCollection();
            if (in_array('penalty', $type)) {
                $responseElseComponent1 = new SetOutcomeValue('SCORE' . $i, new BaseValue(BaseType::FLOAT, 0 - $penalty));
            } else {
                $responseElseComponent1 = new SetOutcomeValue('SCORE' . $i, new BaseValue(BaseType::FLOAT, 0));
            }

            $responseElseRuleCollection1->attach($responseElseComponent1);

            // creating feedback outcome
            if (sizeof($feedBackOptions) > 1) {
                $multipleExpression = new ExpressionCollection();
                $variable = new Variable('FEEDBACK');
                $baseValue = new BaseValue(BaseType::IDENTIFIER, 'IDENTIFIER_' . $i);
                $multipleExpression->attach($variable);
                $multipleExpression->attach($baseValue);
                $feedbackResponseComponent = new SetOutcomeValue('FEEDBACK', new Multiple($multipleExpression));
                $responseElseRuleCollection1->attach($feedbackResponseComponent);
            }

            $responseIf1 = new ResponseIf(new Match($responseIfExpressionCollection1), $responseIfRuleCollection1);
            $responseElse1 = new ResponseElse($responseElseRuleCollection1);

            // merge response conditions
            $responseCondition1 = new ResponseCondition($responseIf1, null, $responseElse1);
            $responseRuleCollection->attach($responseCondition);
            $responseElseRuleCollection->attach($responseCondition1);

            // set response rules to responseProcessing
            $responseProcessing = new ResponseProcessing();
            $responseProcessing->setResponseRules($responseRuleCollection);

            // creating expression of scores for final score
            $sumexpressionCollection->attach(new Variable('SCORE' . $i));
            $i++;
        }

        $scoreResponseComponent = new SetOutcomeValue('SCORE', new Sum($sumexpressionCollection));
        $responseRuleCollection->attach($scoreResponseComponent);

        // genrate outcome value if distrator_rationale_value is set
        if (is_array($feedBackOptions) && !empty($feedBackOptions['genral_feedback'])) {
            $responseFeedbackComponent = new SetOutcomeValue('FEEDBACK_GENERAL', new BaseValue(BaseType::IDENTIFIER, 'correctOrIncorrect'));
            $responseRuleCollection->attach($responseFeedbackComponent);
        }

        return $responseProcessing;
    }
}
