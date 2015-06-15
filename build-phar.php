<?php
require_once "vendor/autoload.php";

$phar = new \Phar('build/learnosity-qti.phar', 0, 'learnosity-qti.phar');
$phar->setSignatureAlgorithm(\Phar::SHA1);

$phar->startBuffering();

$finder = new \Symfony\Component\Finder\Finder();
$finder->files()
    ->ignoreVCS(true)
    ->name('*.php')
    ->name('*.yml')
    ->notName('build-phar.php')
    ->in(__DIR__ . '');

$totalFileCount = count($finder);
$i = 0;
echo 'Total File: ' . $totalFileCount . "\n";
echo 'Progress:     ';

foreach ($finder as $file) {
    $phar->addFile($file, $file->getRelativePathName());
    $progress = intval($i++ / $totalFileCount * 100);
    if ($i % 10 === 0) {
        echo "\033[5D";
        echo str_pad($progress, 3, ' ', STR_PAD_LEFT) . " %";
    }

}

$phar->setStub(
    '<?php
        Phar::mapPhar();
        include "phar://learnosity-qti.phar/console.php";
        __HALT_COMPILER();'
);

$phar->stopBuffering();

echo "\n" . 'Process complete successfully';