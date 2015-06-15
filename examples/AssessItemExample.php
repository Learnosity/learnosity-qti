<?php

require_once 'config.php';

if (!isset($sampleFileFolder)) {
    $sampleFileFolder = 'SampleAssessmentItem';
}

if (!isset($binaryPath)) {
    $binaryPath = 'php ../build/learnosity-qti.phar';
    //$binaryPath = '/usr/local/bin/php ' . dirname(__FILE__) . '/../console.php';
}

if (isset($_REQUEST['qti'])) {
    $security = array(
        'consumer_key' => $consumer_key,
        'domain' => $domain,
        'timestamp' => $timestamp
    );

    // prepare to use the phar binary
    $cmd = sprintf('%s convert qti json', $binaryPath);
    $descriptorspec = array(
        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
        2 => array("file", "/tmp/error-output.txt", "a")
    );
    // Special env var needs to be provided for MAC
    $process = proc_open($cmd, $descriptorspec, $pipes, null, array('DYLD_LIBRARY_PATH' => '/usr/lib'));
    if (is_resource($process)) {
        fwrite($pipes[0], $_REQUEST['qti']);
        fclose($pipes[0]);
        $result = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $return_value = proc_close($process);
        $object = json_decode($result, true);
        $item = $object[0];
        $questions = $object[1];
    }

    $activitySignature = hash("sha256", $consumer_key . '_' . $domain . '_'
        . $timestamp . '_' . $studentid . '_' . $consumer_secret);

    $questionsList = [];
    foreach ($questions as $index => $q) {
        $questions[$index]['data']['response_id'] = $item['questionReferences'][$index];
        $questionsList[] = $questions[$index]['data'];
    }

    $activity = [
        'consumer_key' => $consumer_key,
        'timestamp' => $timestamp,
        'signature' => $activitySignature,
        'user_id' => $studentid,
        'type' => 'local_practice',
        'state' => 'initial',
        'id' => 'demo-chart-activity',
        'name' => 'Demo Bart Chart',
        'course_id' => $courseid,
        'questions' => $questionsList,
        'showCorrectAnswers' => true
    ];

    echo json_encode(['layout' => $item['content'], 'activity' => $activity]);
} elseif (isset($_REQUEST['filePath'])) {
    echo file_get_contents($_REQUEST['filePath']);
} else {
    $files = scandir($sampleFileFolder);
    $fileList = [];

    foreach ($files as $f) {
        if (strpos($f, '.xml') !== false) {
            $fileList[$f] = $sampleFileFolder . DIRECTORY_SEPARATOR . $f;
        }
    }
}

?>

<?php if ($_SERVER['REQUEST_METHOD'] === 'GET'): ?>
    <html>
    <head>
        <title>Learnosity Documentation - Offline Package API demo</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <script src="http://underscorejs.org/underscore-min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/styles/default.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js"></script>
        <style>
            .xml-text, .lrn-json {
                min-height: 600px;
                min-width: 570px;
            }

            .output-json-row {
                padding-top: 10px;
            }

            .error-message {
                background: indianred;
            }

            .validate-response {
                float: right;
            }
        </style>
    </head>

    <div class="container">
        <div>
            <h1>QTI Assessment Item Demo</h1>
        </div>

        <div class="row">
            <div class="col-md-6">
                <p>
                    <select id="fileSelect">
                        <?php
                        foreach ($fileList as $key => $value) {
                            echo '<option value="' . $value . '">' . $key . '</option>';
                        }
                        ?>
                    </select>
                    <button id="loadFile" type="button" class="btn btn-primary">Load</button>
                    <button id="submit" type="button" class="btn btn-primary">Parse</button>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <p><span class="label label-default">QTI XML</span></p>
                <textarea id="qti-xml" class="xml-text" name="qtiXML" type="text"></textarea>
            </div>
            <div class="col-md-6">
                <p>
                    <span class="label label-default">Render Result</span>
                    <span class="validate-response">
                        <input type="button" id="validateResponse" value="Validate Question">
                    </span>
                </p>

                <div id="errorMsg" class="error-message"></div>
                <div id="render-wrapper"></div>
            </div>
        </div>

        <div class="row output-json-row">
            <div class="col-md-12">
                <p><span class="label label-default">Converted Json Data</span></p>
                <pre><code id="outputJson" class="html"></code></pre>
            </div>
        </div>
    </div>

    <script src="//questions.learnosity.com/?latest"></script>
    <script>
        var questionsApp;
        $(function () {


            $('#submit').click(function () {
                var requestXML = $('#qti-xml').val();
                $('#errorMsg').html('');
                $.ajax({
                    type: "POST",
                    url: '',
                    data: 'qti=' + requestXML,
                    success: function (data) {
                        try {
                            var result = JSON.parse(data);
                            $('#render-wrapper').html(result.layout);
                            questionsApp = LearnosityApp.init(result.activity);
                            $('#outputJson').text(JSON.stringify(result, null, 4));
                            console.log(result);
                            hljs.initHighlightingOnLoad();
                        } catch (err) {
                            $('#errorMsg').html(data);
                        }

                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            });

            $('#loadFile').click(function () {
                var requestFileName = $('#fileSelect').val();

                console.log(requestFileName);
                $.ajax({
                    type: "POST",
                    url: '',
                    data: 'filePath=' + requestFileName,
                    success: function (data) {
                        $('#qti-xml').html(data);
                    }
                });
            });

            $('#validateResponse').click(function () {
                questionsApp.validateQuestions()
            });
        });
    </script>
    </html>


<?php endif; ?>
