<?php
$dir = "/var/www/html/assets/images/";
$tmp = tempnam("/tmp", "phptest");
file_put_contents($tmp, "hello test");

echo "tmp file: $tmp\n";
echo "tmp is_readable: " . (is_readable($tmp) ? "yes" : "no") . "\n";
echo "dest is_writable: " . (is_writable($dir) ? "yes" : "no") . "\n";

// Test rename (what move_uploaded_file uses internally)
$dest = $dir . "renametest.txt";
$r = rename($tmp, $dest);
echo "rename result: " . var_export($r, true) . "\n";
if (!$r) {
    echo "rename error: " . error_get_last()["message"] . "\n";
    // Fallback: try copy
    $tmp2 = tempnam("/tmp", "phptest");
    file_put_contents($tmp2, "hello test");
    $r2 = copy($tmp2, $dir . "copytest.txt");
    echo "copy result: " . var_export($r2, true) . "\n";
    if (!$r2) echo "copy error: " . error_get_last()["message"] . "\n";
    unlink($tmp2);
}
if (file_exists($dest)) unlink($dest);
if (file_exists($dir . "copytest.txt")) unlink($dir . "copytest.txt");
