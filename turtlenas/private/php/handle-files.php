<?php

// Function to fetch file list from directory.
function scanDirAndSubdir($dir, &$out = []) {
    $sun = scandir($dir);

    foreach ($sun as $a => $filename) {
        $way = realpath($dir . DIRECTORY_SEPARATOR . $filename);
// List Files.
        if (!is_dir($way)) {
            $out[] = $way;
// List Directories.
        } else if ($filename != "." && $filename != "..") {
            scanDirAndSubdir($way, $out);
            $out[] = ("$way/");
        }
    }

    return $out;
}

// Directory to fetch. (edit path with variables "/media/$vLOCATION/$vDRIVE/$vUSER" for better reference by databases)
$afiles = (scanDirAndSubdir("/media/Local/local/$username"));

// List files in a browser format.
foreach ($afiles as $a2) {
    echo "<a href='/download.php?$a2'>$a2</a><br>";
}


?>
