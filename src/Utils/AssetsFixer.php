<?php

namespace LearnosityQti\Utils;

use Exception;
use LearnosityQti\Utils\General\StringHelper;
use LearnositySdk\Utils\Json;

class AssetsFixer
{
    private array $urlMap = [];
    private $organisationId;

    public function __construct($organisationId)
    {
        $this->organisationId = $organisationId;
    }

    /**
     * @throws Exception
     */
    public function fix(array $array)
    {
        $encodedArray = Json::encode($array);
        // Replace HTML Urls
        $urls = $this->parseAssetsUrls($encodedArray);

        foreach ($urls as $url) {
            // Ignore stupid base64
            if (!StringHelper::contains($url, 'base64')) {
                $filename = StringHelper::contains($url, '.svgz') ?
                    basename($url, '.svgz') . '.svg'
                    : basename($url);

                $replacement = 'https://assets.learnosity.com/organisations/'
                    . $this->organisationId
                    . '/'
                    . $filename;

                $encodedArray = str_replace($url, $replacement, $encodedArray);
                $this->urlMap[$url] = $replacement;
            }
        }

        // Replace non-HTML Urls, ie. data['image']['src']
        // TODO actually support things like data['image']['src'] in the conversion lib
        $encodedArray = str_replace('"assets/', '"https://assets.learnosity.com/organisations/' . $this->organisationId . '/', $encodedArray, $count1);
        $encodedArray = str_replace('"../images/', '"https://assets.learnosity.com/organisations/' . $this->organisationId . '/', $encodedArray, $count1);
        $encodedArray = str_replace('"../Content/Images/', '"https://assets.learnosity.com/organisations/' . $this->organisationId . '/', $encodedArray, $count1);
        $encodedArray = str_replace('.svgz"', '.svg"', $encodedArray);

        // TODO: This is a hack because those audio/video files are not enclosed in proper folders
//        $encodedArray = preg_replace('/"([^"]+)(.m4a)/', '"https://assets.learnosity.com/organisations/' . $this->organisationId . '/$1.mp3', $encodedArray);
//        $encodedArray = preg_replace('/"([^"]+)(.mp4)/', '"https://assets.learnosity.com/organisations/' . $this->organisationId . '/$1.mp4', $encodedArray);

        // Encode
        $result = json_decode($encodedArray, true);

        if (empty($result)) {
            throw new Exception('Image replacement fails');
        }

        return $result;
    }

    private function parseAssetsUrls($content): array
    {
        // Check for URLs surrounded by double quotes
        preg_match_all(
            '/<(img|audio)[^>]*src="([^"]*)\"/i',
            $content,
            $urls
        );

        // Merge the result
        $uris = array_unique($urls[2]);

        // Remove trailing '\' character if exist upon returning
        return array_map(function ($uri) {
            return rtrim($uri, "\\");
        }, $uris);
    }

    public function getUrlMap()
    {
        return $this->urlMap;
    }
}
