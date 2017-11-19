<?php


namespace AppBundle\Component\Helper;


class DirectoryHelper
{

// Does not support flag GLOB_BRACE
    public static function glob_recursive($pattern, $flags = 0)
    {
        $files[] = glob($pattern, $flags);
        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files[] = self::glob_recursive($dir . '/' . basename($pattern), $flags);
        }
        return array_merge(...$files);
    }
}