<?php

namespace R64\NovaFields\Http\Services;

trait FileFunctions
{
    /**
     * @param $path
     *
     * @return string
     */
    public function checkPerms($path)
    {
        clearstatcache(null, $path);

        return decoct(fileperms($path) & 0777);
    }

    /**
     * @param $size
     * @param int $level
     * @param int $precision
     * @param int $base
     *
     * @return string
     */
    public function formatBytes($size, $level = 0, $precision = 2, $base = 1024)
    {
        $unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $times = floor(log($size, $base));

        return sprintf('%.'.$precision.'f', $size / pow($base, ($times + $level))).' '.$unit[$times + $level];
    }

    /**
     * Clean Slashes.
     *
     * @param $str
     *
     * @return string
     */
    public function cleanSlashes($str)
    {
        return preg_replace('/([^:])(\/{2,})/', '$1/', $str);
    }

    /**
     * Cleanup filename.
     *
     * @param  string  $str
     *
     * @return string
     */
    public function fixFilename($str)
    {
        if (! mb_detect_encoding($str, 'UTF-8', true)) {
            $str = utf8_encode($str);
        }
        if (function_exists('transliterator_transliterate')) {
            $str = transliterator_transliterate('Any-Latin; Latin-ASCII', $str);
        } else {
            $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        }
        $str = preg_replace("/[^a-zA-Z0-9\.\[\]_| -]/", '', $str);

        $str = str_replace(['"', "'", '/', '\\'], '', $str);
        $str = strip_tags($str);

        return trim($str);
    }

    /**
     * Cleanup directory name.
     *
     * @param  string  $str
     *
     * @return  string
     */
    public function fixDirname($str)
    {
        return str_replace(['.', '~', '/', '\\'], '', $str);
    }
}
