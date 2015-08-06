<?php

function readFilesIn($folderName, $extensionFilter)
{
    $files = scandir($folderName);
    $fileList = [];
    foreach ($files as $f) {
        if (strpos($f, '.' . $extensionFilter) !== false) {
            $fileList[$f] = $folderName . DIRECTORY_SEPARATOR . $f;
        }
    }
    return $fileList;
}

function convertToQti($input)
{
    require_once "../vendor/autoload.php";

    $postdata = $input;
    $converter = new \Learnosity\Converter();
    $result = $converter->convertLearnosityToQtiItem($postdata);
    return json_encode([
        'xmlString' => $result[0],
        'messages' => $result[1]
    ]);
}

function convertToJson($input)
{
    require_once "../vendor/autoload.php";

    $consumer_key = 'yis0TYCu7U9V4o7M';
    $consumer_secret = '74c5fd430cf1242a527f6223aebd42d30464be22';
    $domain = $_SERVER['SERVER_NAME'];
    $timestamp = gmdate('Ymd-Hi');
    $studentid = 'demo_student';

    $postdata = $input;
    if (empty($postdata)) {
        print_r('There is no XML to parse');
        return;
    }

    $converter = new \Learnosity\Converter();
    $baseAssetsUrl = empty($_GET['baseAssetsUrl']) ? '' : $_GET['baseAssetsUrl'];
    list($item, $questions, $exceptions) = $converter->convertQtiItemToLearnosity($postdata, $baseAssetsUrl);

    $originalQuestions = $questions;
    $questionsList = [];
    foreach ($questions as $index => $q) {
        $questions[$index]['data']['response_id'] = $item['questionReferences'][$index];
        $questionsList[] = $questions[$index]['data'];
    }

    $activity = [
        'consumer_key' => $consumer_key,
        'timestamp' => $timestamp,
        'signature' => hash("sha256", $consumer_key . '_' . $domain . '_' . $timestamp . '_' . $studentid . '_' . $consumer_secret),
        'user_id' => 'demo_student',
        'type' => 'local_practice',
        'state' => 'initial',
        'id' => 'demo-chart-activity',
        'name' => 'Demo Bart Chart',
        'course_id' => 'demo_' . $consumer_key,
        'questions' => $questionsList,
        'showCorrectAnswers' => true
    ];

    return json_encode(
        [
            'layout' => isset($item['content']) ? $item['content'] : '',
            'activity' => $activity,
            'item' => $item,
            'questions' => $originalQuestions,
            'exceptions' => $exceptions
        ]
    );
}
