<?php

require_once 'vendor/autoload.php';

use Learnosity\Converter;
use Learnosity\Exceptions\BaseKnownException;
use Learnosity\Exceptions\RequestException;
use Slim\Slim;

$app = new Slim();

$app->get('/:version/', function ($version) use ($app) {
    echo json_encode([
        'version' => $version,
        'heartbeat' => 'OK'
    ]);
    die;
});

$app->post('/:version/fromqti', function () use ($app) {
    echo execute($app, function () use ($app) {
        $body = json_decode($app->request()->getBody(), true);

        // TODO: At the moment limit request item to 1 (Michael's request)
        if (!isset($body['items']) || !is_array($body['items']) || count($body['items']) !== 1) {
            throw new RequestException('Invalid request object, QTI to Learnosity transformation required `items` as array with one `xml` string');
        }
        $baseAssetsUrl = isset($body['base_asset_path']) ? $body['base_asset_path'] : '';
        $validate = isset($body['validate']) ? filter_var($body['validate'], FILTER_VALIDATE_BOOLEAN) : true;

        $result = [];
        foreach ($body['items'] as $xmlString) {
            list($itemData, $questionsData, $manifest) =
                Converter::convertQtiItemToLearnosity($xmlString, $baseAssetsUrl, $validate);
            $result[] = [
                'item' => $itemData,
                'questions' => $questionsData,
                'manifest' => $manifest
            ];
        }
        return $result;
    });
});

$app->post('/:version/toqti', function () use ($app) {
    echo execute($app, function() use ($app) {
        $body = json_decode($app->request()->getBody(), true);

        $result = [];
        // TODO: At the moment limit request item to 1 (Michael's request)
        if (!isset($body['items']) || !is_array($body['items']) || count($body['items']) !== 1) {
            throw new RequestException('Invalid request object, QTI to Learnosity transformation required `items` as array with one `item` object');
        }
        foreach ($body['items'] as $item) {
            list($qti, $manifest) = Converter::convertLearnosityToQtiItem($item);
            $result[] = [
                'item' => $qti,
                'manifest' => $manifest
            ];
        }
        return $result;
    });
});

function execute(Slim $app, callable $callback)
{
    try {
        $response = $callback();
    } catch (BaseKnownException $e) {
        $app->response->setStatus(400);
        $response = $e->getMessage();
    } catch (Exception $e) {
        $app->response->setStatus(500);
        $response = 'Error processing conversion request';
    }

    return json_encode($response);
}

$app->run();
