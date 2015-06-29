<?php

namespace Learnosity\Mappers\QtiV2\Import;

use Learnosity\Entities\Activity\activity;
use Learnosity\Entities\Activity\activity_data;
use Learnosity\Entities\Activity\activity_data_config;
use Learnosity\Entities\Activity\activity_data_config_time;
use Learnosity\Exceptions\MappingException;
use qtism\data\AssessmentTest;
use qtism\data\ExtendedAssessmentItemRef;
use qtism\data\storage\xml\XmlDocument;
use qtism\data\storage\xml\XmlStorageException;

class TestMapper
{
    public function parse($xmlString)
    {
        try {
            $document = new XmlDocument();
            $document->loadFromString($xmlString);

            //get all assessmentItemRef
            /* @var $assessmentTest AssessmentTest*/
            $assessmentTest = $document->getDocumentComponent();
            $activityReference = $assessmentTest->getIdentifier();
            $description = $assessmentTest->getTitle();
            $timeLimitElement = $assessmentTest->getTimeLimits();
            $timeConfig = new activity_data_config_time();
            if($timeLimitElement) {
                $interval = $timeLimitElement->getMaxTime();
                $timeConfig->set_max_time($interval->getSeconds(true));
                $limitType = $timeLimitElement->doesAllowLateSubmission() ? 'soft': 'hard';
                $timeConfig->set_limit_type($limitType);
            }

            $assessmentItemRefs = $assessmentTest->getComponentsByClassName('assessmentItemRef', true);
            $activityItemsList = [];
            /* @var $assessmentItemRef ExtendedAssessmentItemRef*/
            foreach($assessmentItemRefs as $assessmentItemRef) {
                $activityItemsList[$assessmentItemRef->getIdentifier()] = $assessmentItemRef->getHref();
            }

            $activityData = new activity_data();
            $activityConfig = new activity_data_config();
            $activityConfig->set_time($timeConfig);
            $activityData->set_config($activityConfig);
            $activity = new activity($activityReference, $activityData);
            $activity->set_items(array_keys($activityItemsList));
            $activity->set_description($description);

            return [$activity, $activityItemsList];

        } catch (XmlStorageException $e) {
            $previousException = $e->getPrevious();
            $msg = $e->getMessage(). "\n";
            if($previousException) {
                $previousException->getMessage();
            }
            throw new MappingException($msg, MappingException::CRITICAL, $previousException);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
