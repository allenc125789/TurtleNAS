<?php


function scanDirAndSubdir($dir, &$out = []) {
    $sun = scandir($dir);

    foreach ($sun as $a => $filename) {
        $way = realpath($dir . DIRECTORY_SEPARATOR . $filename);
        if (!is_dir($way)) {
            $out[] = $way;
        } else if ($filename != "." && $filename != "..") {
            scanDirAndSubdir($way, $out);
            $out[] = $way;
        }
    }

    return $out;
}

$afiles = (scanDirAndSubdir("/media/Local/local/$username"));

$link = filelink();
foreach ($afiles as $a2) {
    echo "<a href='$a2'>$a2</a><br>";
}


?>
