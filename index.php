<?php

require 'vendor/autoload.php';

$app = new \Slim\Slim();
$converter = new \Learnosity\Converter();

$app->post('/', function () use ($app, $converter) {
    try {
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
