<?php

namespace LearnosityQti\Utils;

use LearnosityQti\Services\LogService;
use LearnositySdk\Utils\Uuid;
use qtism\common\datatypes\QtiDirectedPair;
use qtism\common\datatypes\QtiFloat;
use qtism\common\datatypes\QtiIdentifier;
use qtism\common\datatypes\QtiString;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\AssessmentItem;
use qtism\data\state\CorrectResponse;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;
use qtism\runtime\common\MultipleContainer;
use qtism\runtime\common\ResponseVariable;
use qtism\runtime\common\State;
use qtism\runtime\tests\AssessmentItemSession;

class ItemScoringGuesser
{
    const PARTIALLY_CORRECT_FIRST = 0;
    const PARTIALLY_CORRECT_LAST = 1;

    public static function guessWithRules(AssessmentItem $assessmentItem)
    {
        $responseDeclarations = $assessmentItem->getResponseDeclarations();

        // If only 1, dont worry about it just assume per question
        if (count($responseDeclarations) == 1) {
            return 'per-question';
        }

        // Check max score
        $maxScore = self::getScoreForFullyCorrectResponseVariables($assessmentItem);
        $firstQuestionWrongScore = self::getScoreForPartiallyCorrectResponseVariables($assessmentItem, self::PARTIALLY_CORRECT_FIRST);
        $lastQuestionWrongScore = self::getScoreForPartiallyCorrectResponseVariables($assessmentItem, self::PARTIALLY_CORRECT_LAST);

        // Assume nothing bad
        if ($maxScore === $firstQuestionWrongScore || $maxScore === $lastQuestionWrongScore) {
            AssumptionHandler::log('Validation does not seems right, please take a look on this. Assumed as `per-question`');
            return 'per-question';
        }

        // Guess dah` scoring type!
        $guessed = self::guessBasedOnScores($firstQuestionWrongScore, $lastQuestionWrongScore);
        if (empty($guessed)) {
            AssumptionHandler::log('Validation is invalid, please take a look on this. Assumed as `per-question`');
            return 'per-question';
        }
        // Don't worry about putting warning if question only 2
        if ($responseDeclarations->count() != 2) {
            AssumptionHandler::log('Complex response processing processed. Please check');
        }
        return $guessed;
    }

    private static function guessBasedOnScores($firstQuestionWrongScore, $lastQuestionWrongScore)
    {
        // Test with a single incorrect answer (first vs. last)
        if ($firstQuestionWrongScore != $lastQuestionWrongScore) {
            // If the answer is not the same, prob this is EBSR! They are dependent, bro!
            if ($firstQuestionWrongScore == 0) {
                return 'dependent';
            }
        } else {
            // If both of them (partially correct) got no score, prob this dichotomous
            if ($firstQuestionWrongScore == 0 && $lastQuestionWrongScore == 0) {
                return 'dichotomous';
            } elseif ($firstQuestionWrongScore != 0 && $lastQuestionWrongScore != 0) {
                return 'per-question';
            }
        }
        return false;
    }

    private static function getScoreForFullyCorrectResponseVariables(AssessmentItem $assessmentItem)
    {
        $itemSession = new AssessmentItemSession($assessmentItem);
        $itemSession->beginItemSession();
        $itemSession->beginAttempt();

        // Get answers
        $responseVariables = [];
        foreach ($assessmentItem->getResponseDeclarations() as $responseDeclaration) {
            /** @var ResponseDeclaration $responseDeclaration */
            $responseVariables[] = self::buildResponseVariable($itemSession, $responseDeclaration);
        }
        $itemSession->endAttempt(new State(array_filter($responseVariables)));
        $score = $itemSession['SCORE']->getValue();
        return $score;
    }

    private static function getScoreForPartiallyCorrectResponseVariables(AssessmentItem $assessmentItem, $type)
    {
        $itemSession = new AssessmentItemSession($assessmentItem);
        $itemSession->beginItemSession();
        $itemSession->beginAttempt();

        // Get answers
        $responseVariables = [];
        $responseDeclarations = array_values($assessmentItem->getResponseDeclarations()->getArrayCopy(true));
        foreach ($responseDeclarations as $index => $responseDeclaration) {
            /** @var ResponseDeclaration $responseDeclaration */

            if ($type === self::PARTIALLY_CORRECT_FIRST) {
                $responseVariables[] = ($index == 0) ?
                    self::buildIncorrectResponseVariable($itemSession, $responseDeclaration)
                    : self::buildResponseVariable($itemSession, $responseDeclaration);
            } else if ($type === self::PARTIALLY_CORRECT_LAST) {
                $responseVariables[] = ($index == count($responseDeclarations) - 1) ?
                    self::buildIncorrectResponseVariable($itemSession, $responseDeclaration)
                    : self::buildResponseVariable($itemSession, $responseDeclaration);
            }
        }
        $itemSession->endAttempt(new State(array_filter($responseVariables)));
        $score = $itemSession['SCORE']->getValue();
        return $score;
    }
    private static function buildIncorrectResponseVariable(AssessmentItemSession $itemSession, ResponseDeclaration $responseDeclaration)
    {
        return self::buildResponseVariable($itemSession, $responseDeclaration, false);
    }
    private static function buildResponseVariable(AssessmentItemSession $itemSession, ResponseDeclaration $responseDeclaration, $correct = true)
    {
        $correctResponse = $responseDeclaration->getCorrectResponse();
        if (!empty($correctResponse)) {
            $correctResponseVariable = self::getCorrectResponseVariable($responseDeclaration, $correctResponse);
            if (empty($correctResponseVariable)) {
                return null;
            }
            return ($correct) ? $correctResponseVariable : self::getIncorrectResponseVariable($correctResponseVariable);
        }
        // Let make assumption for manual scoring, our outcome would start with `SCORE_` and set score 1
        // TODO: Because upon `endAttempt` this outcome going to be reset, we hack it by forcing its default value to 1
        $outcomeIdentifier = str_replace('RESPONSE', 'SCORE', $responseDeclaration->getIdentifier());
        $outcomeVariable = $itemSession->getOutcomeVariables()->getVariable($outcomeIdentifier);
        if (isset($outcomeVariable) && $correct) {
            $outcomeVariable->setDefaultValue(new QtiFloat(1.0));
        }
        return $outcomeVariable;
    }

    private static function getIncorrectResponseVariable(ResponseVariable $correctResponseVariable)
    {
        $identifier = $correctResponseVariable->getIdentifier();
        $cardinality = $correctResponseVariable->getCardinality();
        $baseType = $correctResponseVariable->getBaseType();
        $value = $correctResponseVariable->getValue();

        if ($baseType === BaseType::IDENTIFIER) {
            $responseValue = null;
        } else if ($baseType === BaseType::STRING) {
            $responseValue = new QtiString(Uuid::generate());
        } else if ($baseType === BaseType::FLOAT) {
            $responseValue = new QtiFloat($value->getValue() + '100');
        } else if ($baseType === BaseType::PAIR) {
            $responseValue = null;
        } else if ($baseType === BaseType::DIRECTED_PAIR) {
            $responseValue = null;
        } else {
            LogService::log('ItemScoringGuesser: Implement getIncorrectResponseVariable( '.
                'baseType='.BaseType::getNameByConstant($baseType)."($baseType), ".
                'cardinality='.Cardinality::getNameByConstant($cardinality)."($cardinality), ".
                ')');
            // die;
            return;
        }
        return new ResponseVariable($identifier, $cardinality, $baseType, $responseValue);
    }

    private static function getCorrectResponseVariable(ResponseDeclaration $responseDeclaration, CorrectResponse $correctResponse)
    {
        $responseValue = null;

        $correctResponseValues = $correctResponse->getValues()->getArrayCopy();
        $identifier = $responseDeclaration->getIdentifier();
        $cardinality = $responseDeclaration->getCardinality();
        $baseType = $responseDeclaration->getBaseType();

        if ($cardinality === Cardinality::SINGLE) {
            if ($baseType === BaseType::IDENTIFIER) {
                $responseValue = new QtiIdentifier($correctResponseValues[0]->getValue());
            } else if ($baseType === BaseType::STRING) {
                $responseValue = new QtiString($correctResponseValues[0]->getValue());
            } else if ($baseType === BaseType::FLOAT) {
                $responseValue = new QtiFloat($correctResponseValues[0]->getValue());
            } else {
                echo 'Implement this base type handler with cardinality single!';
                die;
            }
        } elseif ($cardinality === Cardinality::MULTIPLE) {
            if ($baseType === BaseType::IDENTIFIER) {
                $responseValue = new MultipleContainer(BaseType::IDENTIFIER);
                foreach ($correctResponseValues as $value) {
                    /** @var Value $value */
                    $responseValue->attach(new QtiIdentifier($value->getValue()));
                }
            } else if ($baseType === BaseType::STRING) {
                $responseValue = new MultipleContainer(BaseType::STRING);
                foreach ($correctResponseValues as $value) {
                    /** @var Value $value */
                    $responseValue->attach(new QtiString($value->getValue()));
                }
            } else if ($baseType === BaseType::PAIR) {
                $responseValue = new MultipleContainer(BaseType::PAIR);
                foreach ($correctResponseValues as $value) {
                    /** @var Value $value */
                    $responseValue->attach($value->getValue());
                }
                AssumptionHandler::log('Complex Pair validation rules - Need check');
            } else if ($baseType === BaseType::DIRECTED_PAIR) {
                $responseValue = new MultipleContainer(BaseType::DIRECTED_PAIR);
                // Yes for correct response, we can't just copy from the correct answer sheet
                // Let's assume not unique first, let see what happen
                $uniqueIdentifiers = [];
                foreach ($correctResponseValues as $correctResponseValue) {
                    /** @var QtiDirectedPair $value */
                    $value = $correctResponseValue->getValue();
                    $first = $value->getFirst();
                    $second = $value->getSecond();
                    // So lets try avoid duplicates
                    // TODO: Might not be the case but just do it anyway
                    if (!in_array($first, $uniqueIdentifiers) && !in_array($second, $uniqueIdentifiers)) {
                        $responseValue->attach($value);
                        $uniqueIdentifiers[] = $first;
                        $uniqueIdentifiers[] = $second;
                    }
                    AssumptionHandler::log('Complex Directed Pair validation rules - Need check');
                }
            } else {
                echo 'Implement this base type handler with cardinality multiple!';
                die;
            }
        } else {
            LogService::log('ItemScoringGuesser: Implement getCorrectResponseVariable( '.
                'baseType='.BaseType::getNameByConstant($baseType)."($baseType), ".
                'cardinality='.Cardinality::getNameByConstant($cardinality)."($cardinality), ".
                ')');
            // die;
            return;
        }

        return new ResponseVariable($identifier, $cardinality, $baseType, $responseValue);
    }
}
