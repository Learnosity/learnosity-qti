<?php

namespace Learnosity\Utils;

use Exception;
use Symfony\Component\Finder\SplFileInfo;

class FileSystemUtil
{
    public static function readFile($path)
    {
        $file = new SplFileInfo($path, '', '');
        if (!$file->isFile()) {
            throw new Exception('Invalid file. Fail to get file ' . $path);
        }
        return $file;
    }

    public static function readJsonContent($path)
    {
        $file = self::readFile($path);
        if (empty($file)) {
            throw new Exception('Invalid file. Fail to read content on ' . $path);
        }
        return json_decode($file->getContents(), true);
    }

    public static function getRootPath()
    {
        return dirname(__FILE__) . '/../..';
    }

    public static function recursiveRemoveDirectory($directory)
    {
        foreach (glob("{$directory}/*") as $file) {
            if (is_dir($file)) {
                self::recursiveRemoveDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($directory);
    }
}
