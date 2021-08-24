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
    throw new Exception("Mysqli exception" . ($when ? " when $when" : "") . ": " . mysqli_error($link));
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

// format "2021-01-01 01:01:01" - UTC always
function recycleTimestamp() : string {
    return gmdate('Y-m-d H:i:s');
}

// https://stackoverflow.com/a/31107425/11103179
function random_str(
    int $length = 20,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ($length < 1) {
        throw new Exception("Length must be a positive integer");
    }
    $pieces = "";
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces .= $keyspace[random_int(0, $max)];
    }
    return $pieces;
}
