<?php

$vendorDirs = [
    __DIR__ . '/vendor', // standalone package
    __DIR__ . '/../..',  // embedded in another package
];

// Locate the autoload.
$loader = null;

foreach ($vendorDirs as $vendorDir) {
    $file = "$vendorDir/autoload.php";

    if (file_exists($file)) {
        $loader = require_once($file);
        break;
    }
}
