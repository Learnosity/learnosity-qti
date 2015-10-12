<?php

namespace LearnosityQti\Utils;

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

    public static function getPathType($path)
    {
        $fileInfo = new SplFileInfo($path, '', '');
        if ($fileInfo->isDir()) {
            return self::PATH_TYPE_DIRECTORY;
        } elseif ($fileInfo->isFile()) {
            $ext = $fileInfo->getExtension();
            switch ($ext) {
                case 'zip':
                    return self::PATH_TYPE_ZIP;
                default:
                    return self::PATH_TYPE_FILE;
            }
        }
        return self::PATH_TYPE_UNKNOWN;
    }

    public static function createWorkingFolder($rootPath = '/tmp', $prefix = '', $suffix = '')
    {
        $folderName = $prefix . '_' . StringUtil::generateRandomString(6) . '_' . $suffix;
        $folderName = $rootPath . DIRECTORY_SEPARATOR . $folderName;

        if (!@mkdir($folderName, 0700, true)) {
            $error = error_get_last();
            throw new Exception($error['message']);
        }
        return $folderName;
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
