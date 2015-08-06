<?php

require 'src/latest/vendor/autoload.php';

$app = new \Slim\Slim();

// TODO: This quick and dirty endpoint is used for regression testing on the demo page upon converting Learnosity JSON to QTI
// TODO: Should only be used to `sort of` ensure our QTI produced by this library is valid and renderable
$app->post('/renderQtiWithTao', function () use ($app) {

    $document = new \qtism\data\storage\xml\XmlDocument();
    $document->loadFromString($app->request()->getBody());

    $engine = new \qtism\runtime\rendering\markup\xhtml\XhtmlRenderingEngine();
    $renderResult = $engine->render($document->getDocumentComponent());
    $taoRenderResult = $renderResult->saveXml($renderResult->documentElement);

    $app->response->setStatus(200);
    echo json_encode([
        'xhtml' => $taoRenderResult
    ]);
    die;
});

$app->post('/', function () use ($app) {
    try {
        $converter = new \Learnosity\Converter();

        $body = json_decode($app->request()->getBody(), true);

        // Validate request object
        if (!isset($body['data']) || count($body['data']) <= 0) {
            throw new Exception('Invalid request object, `data` are mandatory, and shall not be empty.');
        }
        if (!isset($body['mode']) || !is_string($body['mode']) || !in_array($body['mode'], ['from_qti', 'to_qti'])) {
            throw new Exception('Invalid request object, `mode` are mandatory, string, and should be either `to_qti` or `from_qti`.');
        }

        $data = $body['data'];

        // Handle QTI to Learnosity transformation
        if ($body['mode'] === 'from_qti') {
            if (!isset($data['assessmentItems']) || !is_array($data['assessmentItems'])) {
                throw new Exception('Invalid request object, QTI to Learnosity transformation required `assessmentItems` as array');
            }
            $baseAssetsUrl = isset($body['base_assets_url']) ? $body['base_assets_url'] : '';
            $result = [];
            foreach ($data['assessmentItems'] as $xmlString) {
                list($itemData, $questionsData, $manifest) =
                    $converter->convertQtiItemToLearnosity($xmlString, $baseAssetsUrl);
                $result[] = [
                    'item' => $itemData,
                    'questions' => $questionsData,
                    'manifest' => $manifest
                ];
            }
            $app->response->setStatus(200);
            echo json_encode($result);
            die;

        // Handle Learnosity to QTI transformation
        } elseif ($body['mode'] === 'to_qti') {
            $result = [];
            if (!isset($data['items']) || !is_array($data['items'])) {
                throw new Exception('Invalid request object, QTI to Learnosity transformation required `items` as array');
            }
            foreach ($data['items'] as $item) {
                list($qti, $manifest) = $converter->convertLearnosityToQtiItem($item);
                $result[] = [
                    'assessmentItem' => $qti,
                    'manifest' => $manifest
                ];
            }
            $app->response->setStatus(200);
            echo json_encode($result);
            die;
        }

        // Everything else
        throw new Exception('Error processing request');
    // Handle errors
    } catch (Exception $e) {
        $app->response->setStatus(500);
        echo json_encode([
            'errors' => $e->getMessage()
        ]);
        die;
    }
});

$app->run();
