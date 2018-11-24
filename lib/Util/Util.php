<?php
namespace Mfn\PHP\Analyzer\Util;

class Util
{

    /**
     * Scan given directory for files matching $fileRegex
     *
     * @param string $dir
     * @param string $fileRegex A \RegexIterator compatible regular expression
     * @return \SplFileInfo[]
     */
    public static function scanDir($dir, $fileRegex = '/\.php$/')
    {
        $files = [];
        $iter = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        $iter = new \RegexIterator($iter, $fileRegex);
        /** @var $file \SplFileInfo */
        foreach ($iter as $file) {
            $files[] = $file;
        };
        return $files;
    }

    /**
     * Primitive default PHP warning/notice/whatever to Exception error handler
     *
     * Just make sure you've PHP properly configured to report all errors
     */
    public static function installMinimalError2ExceptionHandler()
    {
        /** @noinspection PhpUnusedParameterInspection */
        set_error_handler(function ($errno, $errstr, $errfile, $errline, $errcontext) {
            $msg = "$errstr in $errfile line $errline";
            throw new \RuntimeException($msg, $errno);
        });
    }
}
