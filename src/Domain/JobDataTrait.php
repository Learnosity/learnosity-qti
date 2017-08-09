<?php

namespace LearnosityQti\Domain;

use Exception;
use LearnositySdk\Utils\Json;
use ReflectionClass;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

trait JobDataTrait
{
    protected function getFileInClassDirectory($filename)
    {
        $class = new ReflectionClass($this);
        $file = new SplFileInfo(dirname($class->getFilename()) . '/' . $filename, '', $filename);
        if (!$file->isFile()) {
            throw new Exception('Invalid file. Fail to get file ' . $filename);
        }
        return $file;
    }

    protected function getFile($path)
    {
        $file = new SplFileInfo($this->directory . $path, '', $path);
        if (!$file->isFile()) {
            throw new Exception('Invalid file. Fail to get file ' . $path);
        }
        return $file;
    }

    protected function readFile($path)
    {
        $file = $this->getFile($path);
        if (empty($file)) {
            throw new Exception('Invalid file. Fail to read content on ' . $path);
        }
        return $file->getContents();
    }

    protected function readJsonFile($filename, $assoc = true)
    {
        $content = $this->readFile($filename);
        return json_decode($content, $assoc);
    }

    protected function processFiles($directory, callable $callback)
    {
        $finder = new Finder();
        /** @var SplFileInfo $file */
        foreach ($finder->files()->in($this->directory . $directory) as $file) {
            call_user_func($callback, $file);
        }
    }

    protected function processJsonChunks($directory, callable $callback)
    {
        $this->processFiles($directory, function ($file) use ($callback) {
            /** @var SplFileInfo $file */
            $file = new SplFileInfo($file->getPathname(), '', '');
            $contents = json_decode($file->getContents(), true);
            foreach ($contents as $data) {
                call_user_func($callback, $data);
            }
        });
    }

    protected function readJsonResponseChunks($directory)
    {
        return $this->readJsonChunks($directory);
    }

    protected function readJsonChunks($directory)
    {
        $datas = [];
        $this->processJsonChunks($directory, function ($data) use (&$datas) {
            $datas[] = $data;
        });
        return $datas;
    }

    protected function writeJsonToFile(array $array, $filename, $flags = null)
    {
        if (count($array)) {
            if (!file_put_contents(
                $this->outputPath . $filename,
                json_encode($array, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT),
                $flags
            )) {
                $error = Json::checkError();
                throw new Exception('Write JSON to file failed: ' . $filename . '. ' . $error);
            }
        }
    }

    protected function writeStringToFile($str, $filename, $flags = null)
    {
        if (is_string($str) && strlen($str)) {
            if (!file_put_contents(
                $this->directory . $filename,
                $str,
                $flags
            )) {
                throw new Exception('Write string to file failed: ' . $filename);
            }
        }
    }

    protected function readInputArgumentType($arg)
    {
        return substr($arg, 0, strpos($arg, ':'));
    }

    protected function readInputArgumentValue($arg)
    {
        return substr($arg, strpos($arg, ':') + 1);
    }

    /**
     * Converts CLI colon delimited arguments (Eg -a format:v2)
     * to an associative array.
     * @param  array $arguments [CLI colon delimted arguments]
     * @return array            [Associative array]
     */
    protected function setupInputArguments($arguments)
    {
        $config = [];

        if (count($arguments)) {
            foreach ($arguments as $arg) {
                $config[$this->readInputArgumentType($arg)] = $this->readInputArgumentValue($arg);
            }
        }

        return $config;
    }
}
