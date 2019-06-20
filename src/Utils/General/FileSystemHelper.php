<?php

namespace LearnosityQti\Utils\General;

use \DirectoryIterator;
use Exception;
use \Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class FileSystemHelper
{
    public static function getDirectoryList($path, $returnTreeStructure = false)
    {
        $finder = new Finder();
        $finder->directories()->in($path);
        $list = [];
        /** @var SplFileInfo $dir */
        foreach ($finder as $dir) {
            if ($returnTreeStructure) {
                $list[$dir->getRelativePathname()] = $dir->getRelativePathname();
            } else {
                $dirPath = explode('/', $dir->getRelativePathname());
                $list[$dir->getRelativePathname()] = [
                    'path' => $dir->getRelativePathname(),
                    'directory' => end($dirPath)
                ];
            }
        }
        return ($returnTreeStructure) ? ArrayHelper::explodeTree($list, '/') : $list;
    }

    public static function createDir($path, $mode = 0777, $recursive = true)
    {
        if (!is_dir($path)) {
            mkdir($path, $mode, $recursive);
        } else {
            throw new Exception('Directory already exists - ' . $path);
        }
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

    /**
     * Removes all files from a directory but
     * leaves the directory intact
     * @param  [string] $path Filesystem path to a folder
     * @return [void]
     */
    public static function truncateDir($path)
    {
        if (is_dir($path)) {
            $files = glob($path . '*', GLOB_MARK);
            foreach ($files as $file) {
                unlink($file);
            }
        }
    }
    
    /**
     * Copy a file, or recursively copy a folder and its contents
     * @see https://stackoverflow.com/questions/2050859/copy-entire-contents-of-a-directory-to-another-using-php/2050909#2050909
     *
     * @param       string   $sourceDirectory    Source path
     * @param       string   $destinationDirectory      Destination path
     * @return      bool     Returns TRUE on success, FALSE on failure
     */
    public static function copyFiles($sourceDirectory, $destinationDirectory)
    {
        // Simple copy for a file
        if (is_file($sourceDirectory)) {
            return copy($sourceDirectory, $destinationDirectory);
        }
        // Make destination directory
        if (!is_dir($destinationDirectory)) {
            mkdir($destinationDirectory);
        }
        // Loop through the folder
        $dir = opendir($sourceDirectory);
        while (($file = readdir($dir)) !== false) {
            // Skip pointers
            if ($file == '.' || $file == '..') {
                continue;
            }
            // Deep copy directories
            static::copyFiles("$sourceDirectory/$file", "$destinationDirectory/$file");
        }
        // Clean up
        closedir($dir);
        return true;
    }
}
