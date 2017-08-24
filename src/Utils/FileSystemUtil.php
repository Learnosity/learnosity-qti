<?php

namespace LearnosityQti\Utils;

use DirectoryIterator;
use Exception;
use Symfony\Component\Finder\SplFileInfo;

class FileSystemUtil
{
    const PATH_TYPE_ZIP = 'zip';
    const PATH_TYPE_DIRECTORY = 'dir';
    const PATH_TYPE_FILE = 'file';
    const PATH_TYPE_UNKNOWN = 'unknown';

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
        // TODO: dodgy, need to remove this
        return dirname(__FILE__) . '/..';
    }

    public static function getTestFixturesPath()
    {
        // TODO: dodgy, need to remove this
        return dirname(__FILE__) . '/../../tests/Fixtures';
    }

    public static function createDirIfNotExists($path, $mode = 0777, $recursive = true)
    {
        if (!is_dir($path)) {
            mkdir($path, $mode, $recursive);
        }
    }

    public static function createOrReplaceDir($path, $mode = 0777, $recursive = true)
    {
        if (!is_dir($path)) {
            mkdir($path, $mode, $recursive);
        } else {
            self::removeDir($path);
            mkdir($path, $mode, $recursive);
        }
    }

    public static function removeDir($path)
    {
        if (is_dir($path)) {
            $dir = new DirectoryIterator($path);
            foreach ($dir as $item) {
                if ($item->isFile()) {
                    unlink($item->getRealPath());
                } elseif (!$item->isDot() && $item->isDir()) {
                    self::removeDir($item->getRealPath());
                }
            }
            rmdir($path);
        }
    }

    public static function writeJsonToFile($array, $pathname)
    {
        file_put_contents(
            $pathname,
            json_encode($array, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT)
        );
    }
}
