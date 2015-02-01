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

use Mfn\PHP\Analyzer\Util\Util;

# Try to locate composer autoloader
$foundAutoload = false;
foreach (['/../../autoload.php', '/vendor/autoload.php'] as $relDir) {
  if (file_exists(__DIR__ . $relDir)) {
    require __DIR__ . $relDir;
    $foundAutoload = true;
    break;
  }
}

if (!$foundAutoload) {
  echo 'Unable to find composer infrastructure, please see' . PHP_EOL;
  echo 'https://github.com/mfn/php-analyzer for installation.' . PHP_EOL;
  exit(1);
}

unset($relDir, $foundAutoload);
error_reporting(E_ALL);
Util::installMinimalError2ExceptionHandler();
