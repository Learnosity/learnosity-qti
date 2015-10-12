<?php

namespace LearnosityQti\Utils;

class CurlUtil
{
    /**
     * Downloads the given URL to the specified destination
     * @param  string $url
     * @param  string $destination
     *
     * @return array
     */
    public static function downloadUrl($url, $destination)
    {
        $fp = fopen($destination, 'wb');

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        $errorNo = curl_errno($ch);
        $errorMessage = curl_error($ch);

        curl_close($ch);
        fclose($fp);

        return [$errorNo, $errorMessage];
    }

    public static function getFileInfo($url)
    {
        $ch = curl_init($url);

        //TODO: Need to decide whether we want a certain timeout for non-responsive server
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);

        curl_exec($ch);
        $result = curl_getinfo($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * @see http://stackoverflow.com/questions/4635936/super-fast-getimagesize-in-php
     */
    public static function getImageSize($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        curl_close($curl);

        $image = imagecreatefromstring($data);
        $width = imagesx($image);
        $height = imagesy($image);

        return [$width, $height];
    }

    public static function prepareUrlForCurl($url)
    {
        $url = StringUtil::startsWith($url, '//') ? 'http:' . $url : $url;

        // URL encode based of MDN spec
        // http://stackoverflow.com/a/19858404
        return preg_replace_callback("{[^0-9a-z_.!~*'();,/?:@&=+$#]}i", function ($m) {
            return sprintf('%%%02X', ord($m[0]));
        }, $url);
    }
}
