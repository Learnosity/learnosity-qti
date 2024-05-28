<?php

namespace LearnosityQti\Domain;

use Exception;
use LearnositySdk\Utils\Json;
use ReflectionClass;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

trait JobDataTrait
{
    /**
     * @throws Exception
     */
    protected function getFileInClassDirectory($filename): SplFileInfo
    {
        $class = new ReflectionClass($this);
        $file = new SplFileInfo(
            dirname($class->getFilename()) . '/' . $filename, '', $filename
        );

        if (!$file->isFile()) {
            throw new Exception('Invalid file. Fail to get file ' . $filename);
        }

        return $file;
    }

    /**
     * @throws Exception
     */
    protected function getFile($path): SplFileInfo
    {
        $file = new SplFileInfo($this->directory . $path, '', $path);
        if (!$file->isFile()) {
            throw new Exception('Invalid file. Fail to get file ' . $path);
        }

        return $file;
    }

    /**
     * @throws Exception
     */
    protected function readFile($path): string
    {
        $file = $this->getFile($path);

        return $file->getContents();
    }

    /**
     * @throws Exception
     */
    protected function readJsonFile($filename, $assoc = true)
    {
        $content = $this->readFile($filename);

        return json_decode($content, $assoc);
    }

    protected function processFiles($directory, callable $callback): void
    {
        $finder = new Finder();

        /** @var SplFileInfo $file */
        foreach ($finder->files()->in($this->directory . $directory) as $file) {
            call_user_func($callback, $file);
        }
    }

    protected function processJsonChunks($directory, callable $callback): void
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

    protected function readJsonResponseChunks($directory): array
    {
        return $this->readJsonChunks($directory);
    }

    protected function readJsonChunks($directory): array
    {
        $data = [];
        $this->processJsonChunks($directory, function ($data) use (&$data) {
            $data[] = $data;
        });

        return $data;
    }

    /**
     * @throws Exception
     */
    protected function writeJsonToFile(
        array $array,
        $filename,
        $flags = 0,
    ): void {
        if (!empty($array)) {
            if (
                !file_put_contents(
                    $filename,
                    json_encode($array, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
                    $flags
                )
            ) {
                $error = Json::checkError();
                throw new Exception('Write JSON to file failed: ' . $filename . '. ' . $error);
            }
        }
    }

    /**
     * @throws Exception
     */
    protected function writeStringToFile($str, $filename, $flags = 0): void
    {
        if (is_string($str) && strlen($str)) {
            if (
                !file_put_contents(
                    $this->directory . $filename,
                    $str,
                    $flags
                )
            ) {
                throw new Exception('Write string to file failed: ' . $filename);
            }
        }
    }

    protected function readInputArgumentType($arg): string
    {
        return substr($arg, 0, strpos($arg, ':'));
    }

    protected function readInputArgumentValue($arg): string
    {
        return substr($arg, strpos($arg, ':') + 1);
    }

    /**
     * Converts CLI colon delimited arguments (Eg -a format:v2)
     * to an associative array.
     *
     * @param array $arguments CLI colon delimited arguments
     *
     * @return array           Associative array
     */
    protected function setupInputArguments(array $arguments): array
    {
        $config = [];

        if (!empty($arguments)) {
            foreach ($arguments as $arg) {
                $config[$this->readInputArgumentType($arg)] = $this->readInputArgumentValue($arg);
            }
        }

        return $config;
    }
}
