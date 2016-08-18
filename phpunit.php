<?php

/**
 * apparat-micropub
 *
 * @category    Apparat
 * @package     Apparat\Object
 * @author      Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @copyright   Copyright © 2016 Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2016 Joschi Kuphal <joschi@kuphal.net> / @jkphl
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *  the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

define('IS_WINDOWS', stripos(php_uname('s'), 'win') > -1);

// Command that starts the built-in web server
$command = sprintf('php -S %s:%d -t %s', WEB_SERVER_HOST, WEB_SERVER_PORT, WEB_SERVER_DOCROOT);
$process = proc_open($command, [['pipe', 'r']], $pipes);
$pstatus = proc_get_status($process);
$pid = $pstatus['pid'];

echo sprintf('%s - Web server started on %s:%d with PID %d', date('r'), WEB_SERVER_HOST, WEB_SERVER_PORT, $pid).PHP_EOL;

register_shutdown_function(function () use ($pid) {
    echo sprintf('%s - Killing process with ID %d', date('r'), $pid).PHP_EOL;
    IS_WINDOWS ? exec("taskkill /F /T /PID $pid") : exec("kill -9 $pid");
});

error_reporting(E_ALL);
$autoloader = __DIR__.'/vendor/autoload.php';
if (!file_exists($autoloader)) {
    echo "Composer autoloader not found: $autoloader".PHP_EOL;
    echo "Please issue 'composer install' and try again.".PHP_EOL;
    exit(1);
}
require $autoloader;
