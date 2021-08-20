<?php
function isNullOrBlank(?string $val) : bool {
    return ($val == null || empty(trim($val))) == true;
}

function check(bool $condition, string $message) {
    if (!$condition) {
        throw new Exception("ERROR: " . $message);
    }
}

/**
 * @throws Exception if $val is Null of Blank
 */
function requireNonNullOrBlank(?string $val, string $varName) {
    if (isNullOrBlank($val)) {
        throw new Exception($varName . " is Null of Blank.");
    }
}

/**
 * @throws Exception if $val is Null of Blank
 */
function requireAllNonNullOrBlank(?string $errorMsg, array $vars) {
    $errorMessage = $errorMsg ?? "Require non-null of not blank: ";
    $errorCaught = false;

    foreach ($vars as $name => $value) {
        if (isNullOrBlank($value)) {
            $errorMessage .= $name . " " ;
            $errorCaught = true;
        }
    }

    if ($errorCaught == true) {
        throw new Exception($errorMessage);
    }
}

function println($msg) {
    echo $msg  . "\n";
}

function isEmpty(array $array) : bool {
    return count($array) == 0;
}

function stringOrNull(?string $value) : ?string {
    if ($value) {
        $trimmed = trim($value);
        if (strlen($trimmed) > 0) {
            return $trimmed;
        }
    }
    return null;
}

// https://www.geeksforgeeks.org/php-startswith-and-endswith-functions/
function endsWith($string, $endString) : bool {
    $len = strlen($endString);
    if ($len == 0) {
        return true;
    }
    return (substr($string, -$len) === $endString);
}

/** @throws Exception */
function throw_mysqli_error($link, ?string $when = null) {
    throw new Exception("Mysqli exception" . ($when ? "when $when" : "") . ": " . mysqli_error($link));
}

/**
 * @param string $dir
 */
function includeOnceAll(string $dir) {
    $path = (endsWith($dir, "/")) ? $dir : ($dir . "/");
    foreach (glob("$path*.php") as $filename) {
        include_once $filename;
    }
}
