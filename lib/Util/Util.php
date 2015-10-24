<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Markus Fischer <markus@fischer.name>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
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
