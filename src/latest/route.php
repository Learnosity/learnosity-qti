<?php

require_once 'vendor/autoload.php';

use Learnosity\Converter;
use Learnosity\Exceptions\MappingException;
use Learnosity\Exceptions\RequestException;

$app = new \Slim\Slim();

$app->get('/:version/', function ($version) use ($app) {
    echo json_encode([
        'version' => $version,
        'heartbeat' => 'OK'
    ]);
    die;
});

$app->post('/:version/', function () use ($app) {
    try {
        $converter = new Converter();
        $body = json_decode($app->request()->getBody(), true);

        // Validate request object
        if (!isset($body['mode']) || !is_string($body['mode']) || !in_array($body['mode'], ['from_qti', 'to_qti'])) {
            throw new RequestException('Invalid request object, `mode` are mandatory, string, and should be either `to_qti` or `from_qti`.');
        }

        // Handle QTI to Learnosity transformation
        if ($body['mode'] === 'from_qti') {
            // TODO: At the moment limit request item to 1 (Michael's request)
            if (!isset($body['items']) || !is_array($body['items']) || count($body['items']) !== 1) {
                throw new RequestException('Invalid request object, QTI to Learnosity transformation required `items` as array with one `xml` string');
            }
            $baseAssetsUrl = isset($body['base_asset_path']) ? $body['base_asset_path'] : '';
            $result = [];
            foreach ($body['items'] as $xmlString) {
                list($itemData, $questionsData, $manifest) =
                    $converter->convertQtiItemToLearnosity($xmlString, $baseAssetsUrl);
                $result[] = [
                    'item' => $itemData,
                    'questions' => $questionsData,
                    'manifest' => $manifest
                ];
            }
            echo json_encode($result);
            die;

        // Handle Learnosity to QTI transformation
        } elseif ($body['mode'] === 'to_qti') {
            $result = [];
            // TODO: At the moment limit request item to 1 (Michael's request)
            if (!isset($body['items']) || !is_array($body['items']) || count($body['items']) !== 1) {
                throw new RequestException('Invalid request object, QTI to Learnosity transformation required `items` as array with one `item` object');
            }
            foreach ($body['items'] as $item) {
                list($qti, $manifest) = $converter->convertLearnosityToQtiItem($item);
                $result[] = [
                    'item' => $qti,
                    'manifest' => $manifest
                ];
            }
            echo json_encode($result);
            die;
        }

        // Everything else
        throw new RequestException('Error processing request');
    // Handle errors
    } catch (RequestException $e) {
        $app->response->setStatus(400);
        echo json_encode($e->getMessage());
    } catch (MappingException $e) {
        $app->response->setStatus(400);
        echo json_encode($e->getMessage());
    } catch (Exception $e) {
        $app->response->setStatus(500);
        echo json_encode('Error processing conversion request');
    }
});

$app->run();
