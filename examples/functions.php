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

function convertToJson($input, $binaryPath)
{
    $consumer_key = 'yis0TYCu7U9V4o7M';

    // Note - Consumer secret should never get displayed on the page - only used for creation of signature server side
    $consumer_secret = '74c5fd430cf1242a527f6223aebd42d30464be22';

    // Some products need the domain as part of the security signature. Demos has been tested on "localhost"
    $domain = $_SERVER['SERVER_NAME'];

    // Generate timestamp in format YYYYMMDD-HHMM for use in signature
    $timestamp = gmdate('Ymd-Hi');

    // Basic variables simulating any user details needed
    $courseid = 'demo_' . $consumer_key;
    $studentid = 'demo_student';
    $teacherid = 'demo_teacher';
    $schoolid = 'demo_school';


    $postdata = $input;
    if (empty($postdata)) {
        print_r('There is no XML to parse');
        return;
    }

    // prepare to use the phar binary
    $baseAssetsUrlCommand = '';
    if (!empty($_GET['baseAssetsUrl'])) {
        $baseAssetsUrlCommand = '--base-assets-url=' . $_GET['baseAssetsUrl'];
    }
    $cmd = sprintf('%s convert %s qti json', $binaryPath, $baseAssetsUrlCommand);
    $descriptorspec = [
        0 => ["pipe", "r"],  // stdin is a pipe that the child will read from
        1 => ["pipe", "w"],  // stdout is a pipe that the child will write to
        2 => ["pipe", "w"]  // stderr is a pipe that the child will read from
    ];
    // Special env var needs to be provided for MAC
    $process = proc_open($cmd, $descriptorspec, $pipes, null, ['DYLD_LIBRARY_PATH' => '/usr/lib']);
    if (is_resource($process)) {
        stream_set_blocking($pipes[2], 0);
        fwrite($pipes[0], $postdata);
        fclose($pipes[0]);
        $result = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $return_value = proc_close($process);
        $object = json_decode($result, true);
        $item = $object[0];
        $questions = $object[1];
        $exceptions = $object[2];
    }

    if ($error) {
        print_r($error);
        return;
    }

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
        'user_id' => $studentid,
        'type' => 'local_practice',
        'state' => 'initial',
        'id' => 'demo-chart-activity',
        'name' => 'Demo Bart Chart',
        'course_id' => $courseid,
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
