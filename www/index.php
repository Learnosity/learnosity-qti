<?php

$requestedVersion = explode('?', explode('/', $_SERVER['REQUEST_URI'])[1])[0];
$src  = __DIR__ . '/../src/';

// Try direct route with the requested version
if (!empty($requestedVersion)) {
    $route = $src . $requestedVersion . '/route.php';
    if (is_readable($route)) {
        require_once $route;
        die;
    }
}

// Loop through these `src` folder and look for folder names which would be an indicative
// of which versions available currently
$availableVersions = [];
foreach (scandir($src) as $result) {
    if ($result === '.' or $result === '..') continue;
    if (is_dir($src . '/' . $result)) {
        $availableVersions[] = $result;
    }
}

// Try to look if we have `rc` tags for the requested version (naive implementation)
// This should only be used in staging. ie. look for `v0.1.0-rc.3` for `v0.1.0`
if (!empty($requestedVersion)) {
    // Reverse sort versions array so the latest version would stay top of the array
    // Then, do a simple string matching to grab be first one
    rsort($availableVersions, SORT_STRING);
    foreach ($availableVersions as $version) {
        $containsRc = strpos($version, '-rc.') !== false;
        $firstPartEqualsToRequestedVersion = explode('-', $version)[0] === $requestedVersion;
        if ($containsRc && $firstPartEqualsToRequestedVersion) {
            $route = $src . $version . '/route.php';
            if (is_readable($route)) {
                require_once $route;
                die;
            }
        }
    }
}

echo json_encode([
    'message' => 'Invalid version. Try `latest` or other available versions',
    'versions' => $availableVersions
]);
die;
