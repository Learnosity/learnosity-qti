<?php

require_once 'config.php';

if (!isset($sampleFileFolder)) {
    $sampleFileFolder = 'samples';
}

if (!isset($binaryPath)) {
    //$binaryPath = 'php ../build/learnosity-qti.phar';
    $binaryPath = '/usr/local/bin/php ' . dirname(__FILE__) . '/../console.php';
}

// handle requests
if (isset($_POST['filePath'])) {
    echo file_get_contents($_REQUEST['filePath']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $security = [
        'consumer_key' => $consumer_key,
        'domain' => $domain,
        'timestamp' => $timestamp
    ];

    $postdata = file_get_contents("php://input");
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

    $activitySignature = hash("sha256", $consumer_key . '_' . $domain . '_'
        . $timestamp . '_' . $studentid . '_' . $consumer_secret);

    $originalQuestions = $questions;
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


    echo json_encode(
        [
            'layout' => isset($item['content']) ? $item['content'] : '',
            'activity' => $activity,
            'item' => $item,
            'questions' => $originalQuestions,
            'exceptions' => $exceptions
        ]);

} elseif (isset($_GET['documentation'])) {
    echo file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'documentation.html');
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $files = scandir($sampleFileFolder);
    $fileList = [];

    foreach ($files as $f) {
        if (strpos($f, '.xml') !== false) {
            $fileList[$f] = $sampleFileFolder . DIRECTORY_SEPARATOR . $f;
        }
    }
    $isDefault = true;
}

?>

<?php if (isset($isDefault)): ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Learnosity Documentation - QTI Assessment Item Demo</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/styles/default.min.css">
        <style>
            .xml-text, .lrn-json {
                min-height: 600px;
                min-width: 570px;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
            }

            .container {
                width: 100%;
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

            .clipboard {
                cursor: pointer;
            }

            .clipboard:hover {
                background-color: #EAEAEA;
            }

            .success, .success:hover {
                background-color: #E9FFED;
            }

        </style>
    </head>

    <div class="container">
        <div>
            <h1>QTI Assessment Item Demo</h1>
        </div>

        <div class="row">
            <div class="col-md-9">
                <p>
                    <select id="fileSelect">
                        <?php
                        foreach ($fileList as $key => $value) {
                            echo '<option value="' . $value . '">' . $key . '</option>';
                        }
                        ?>
                    </select>
                    <input style="width: 500px" id="baseAssetsUrl" placeholder="Base asset url, ie. '//s3...com/organisation/1'" type="text"/>
                    <button id="loadFile" type="button" class="btn btn-primary">Load</button>
                    <button id="submit" type="button" class="btn btn-primary">Parse</button>
                </p>
            </div>
            <div class="col-md-3">
                <div style="float: right"><a target="_blank" href="?documentation">View Documentation</a></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <p><span class="label label-default">QTI XML</span></p>
                <div id="qti-xml" class="xml-text" name="qtiXML"></div>
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
                <p><span class="label label-default">Exceptions and All Things Ignored</span></p>
                <pre class="clipboard"><code id="errorsJson" class="html"></code></pre>
            </div>
        </div>

        <div class="row output-json-row">
            <div class="col-md-12">
                <p><span class="label label-default">Questions API Initialisation Options Data</span></p>
                <pre class="clipboard"><code id="outputJson" class="html"></code></pre>
            </div>
        </div>

        <div class="row output-json-row">
            <div class="col-md-12">
                <p><span class="label label-default">Item Json Data</span></p>
                <pre class="clipboard"><code id="itemOutputJson" class="html"></code></pre>
            </div>
        </div>

        <div class="row output-json-row">
            <div class="col-md-12">
                <p><span class="label label-default">Questions Json Data</span></p>
                <pre class="clipboard"><code id="questionsOutputJson" class="html"></code></pre>
            </div>
        </div>
    </div>

    <script src="http://underscorejs.org/underscore-min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="//questions.learnosity.com/?latest"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.1.9/ace.js" type="text/javascript" charset="utf-8"></script>
    <script>
        var editor = ace.edit("qti-xml");
        editor.getSession().setMode("ace/mode/xml");
        editor.getSession().setUseWrapMode(true);
        editor.setShowPrintMargin(false);
    </script>
    <script>
        var questionsApp;
        $(function () {
            $('#submit').click(function () {
                var requestXML = editor.getValue();
                var baseAssetsUrl = $('#baseAssetsUrl').val();
                $('#errorMsg').html('');
                $('#render-wrapper').html('');
                $.ajax({
                    type: "POST",
                    url: '?baseAssetsUrl=' + baseAssetsUrl,
                    cache: false,
                    data: requestXML,
                    success: function (data) {
                        try {
                            var result = JSON.parse(data);
                            $('#render-wrapper').html(result.layout);
                            questionsApp = LearnosityApp.init(result.activity);
                            $('#errorsJson').text(JSON.stringify(result.exceptions, null, 4));
                            $('#outputJson').text(JSON.stringify(result.activity, null, 4));
                            $('#itemOutputJson').text(JSON.stringify(result.item, null, 4));
                            $('#questionsOutputJson').text(JSON.stringify(result.questions, null, 4));
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
                    cache: false,
                    data: 'filePath=' + requestFileName,
                    success: function (data) {
                        console.log(data);
                        editor.setValue(data);
                    }
                });
            });

            $('#validateResponse').click(function () {
                questionsApp.validateQuestions()
            });

            $('.clipboard').click(copyRange);
        });

        /**
         * Adds copy to clickboard on any element
         * that has a `clipboard` css class.
         */
        function copyRange(ev) {
            var copyNode = ev.target,
                range = document.createRange(),
                success;

            if ($(copyNode).is('code')) {
                copyNode = $(copyNode).parent('pre')[0];
            }

            range.selectNode(copyNode);
            window.getSelection().addRange(range);

            try {
                success = document.execCommand('copy');
            } catch (err) {
                console.log('Unable to copy '.err);
            }

            if (success) {
                $(copyNode).addClass('success');
                setTimeout(function () {
                    $(copyNode).removeClass('success');
                }, 2000);
                console.log('Copied contents to clipboard');
            }

            // Remove the selections - NOTE: Should use
            // removeRange(range) when it is supported
            window.getSelection().removeAllRanges();
        }
    </script>
    </html>


<?php endif; ?>
