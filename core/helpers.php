<?php

/**
 * Check if a directory exists.
 *
 * @param string $path
 * @return boolean
 *
**/
function hasDir($path)
{
    if (!file_exists($path) && !is_dir($path)) {
        return false;
    }
    return  true;
}

function makeDir($dir)
{
    mkdir($dir);
    echo "INFO: Created $dir\n";
}

function makeFile($dir, $content = "")
{
    $file = fopen($dir, "wb");
    fwrite($file, $content);
    fclose($file);
    echo "INFO: Created $dir\n";
    return $file;
}

function removeDir($dir)
{
    if (hasDir($dir)) {
        foreach (scandir($dir) as $file) {
            if ('.' === $file || '..' === $file) {
                continue;
            }
            if (is_dir("$dir/$file")) {
                removeDir("$dir/$file");
            } else {
                unlink("$dir/$file");
            }
        }
        rmdir($dir);
    }
}

function append($path, $data)
{
    $file = file_get_contents($path);
    $content = $data . $file;
    file_put_contents($path, $content);
}

function rgbMode($color)
{
    $hex = str_replace("#", "", $color);

    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    $rgb = [$r, $g, $b];
    return $rgb;
}
