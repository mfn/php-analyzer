<?php
# Generated by mfn/php-reflection-gen 0.0.1
# PHP version 5.4.30, extension standard 5.4.30
function constant($const_name) {}
function bin2hex($data) {}
function hex2bin($data) {}
function sleep($seconds) {}
function usleep($micro_seconds) {}
function time_nanosleep($seconds, $nanoseconds) {}
function time_sleep_until($timestamp) {}
function strptime($timestamp, $format) {}
function flush() {}
function wordwrap($str, $width, $break, $cut) {}
function htmlspecialchars($string, $quote_style, $charset, $double_encode) {}
function htmlentities($string, $quote_style, $charset, $double_encode) {}
function html_entity_decode($string, $quote_style, $charset) {}
function htmlspecialchars_decode($string, $quote_style) {}
function get_html_translation_table($table, $quote_style) {}
function sha1($str, $raw_output) {}
function sha1_file($filename, $raw_output) {}
function md5($str, $raw_output) {}
function md5_file($filename, $raw_output) {}
function crc32($str) {}
function iptcparse($iptcdata) {}
function iptcembed($iptcdata, $jpeg_file_name, $spool) {}
function getimagesize($imagefile, &$info) {}
function getimagesizefromstring($imagefile, &$info) {}
function image_type_to_mime_type($imagetype) {}
function image_type_to_extension($imagetype, $include_dot) {}
function phpinfo($what) {}
function phpversion($extension) {}
function phpcredits($flag) {}
function php_logo_guid() {}
function php_real_logo_guid() {}
function php_egg_logo_guid() {}
function zend_logo_guid() {}
function php_sapi_name() {}
function php_uname() {}
function php_ini_scanned_files() {}
function php_ini_loaded_file() {}
function strnatcmp($s1, $s2) {}
function strnatcasecmp($s1, $s2) {}
function substr_count($haystack, $needle, $offset, $length) {}
function strspn($str, $mask, $start, $len) {}
function strcspn($str, $mask, $start, $len) {}
function strtok($str, $token) {}
function strtoupper($str) {}
function strtolower($str) {}
function strpos($haystack, $needle, $offset) {}
function stripos($haystack, $needle, $offset) {}
function strrpos($haystack, $needle, $offset) {}
function strripos($haystack, $needle, $offset) {}
function strrev($str) {}
function hebrev($str, $max_chars_per_line) {}
function hebrevc($str, $max_chars_per_line) {}
function nl2br($str, $is_xhtml) {}
function basename($path, $suffix) {}
function dirname($path) {}
function pathinfo($path, $options) {}
function stripslashes($str) {}
function stripcslashes($str) {}
function strstr($haystack, $needle, $part) {}
function stristr($haystack, $needle, $part) {}
function strrchr($haystack, $needle) {}
function str_shuffle($str) {}
function str_word_count($str, $format, $charlist) {}
function str_split($str, $split_length) {}
function strpbrk($haystack, $char_list) {}
function substr_compare($main_str, $str, $offset, $length, $case_sensitivity) {}
function strcoll($str1, $str2) {}
function money_format($format, $value) {}
function substr($str, $start, $length) {}
function substr_replace($str, $replace, $start, $length) {}
function quotemeta($str) {}
function ucfirst($str) {}
function lcfirst($str) {}
function ucwords($str) {}
function strtr($str, $from, $to) {}
function addslashes($str) {}
function addcslashes($str, $charlist) {}
function rtrim($str, $character_mask) {}
function str_replace($search, $replace, $subject, &$replace_count) {}
function str_ireplace($search, $replace, $subject, &$replace_count) {}
function str_repeat($input, $mult) {}
function count_chars($input, $mode) {}
function chunk_split($str, $chunklen, $ending) {}
function trim($str, $character_mask) {}
function ltrim($str, $character_mask) {}
function strip_tags($str, $allowable_tags) {}
function similar_text($str1, $str2, &$percent) {}
function explode($separator, $str, $limit) {}
function implode($glue, $pieces) {}
function join($glue, $pieces) {}
function setlocale($category, $locale, $param3) {}
function localeconv() {}
function nl_langinfo($item) {}
function soundex($str) {}
function levenshtein($str1, $str2, $cost_ins, $cost_rep, $cost_del) {}
function chr($codepoint) {}
function ord($character) {}
function parse_str($encoded_string, &$result) {}
function str_getcsv($string, $delimiter, $enclosure, $escape) {}
function str_pad($input, $pad_length, $pad_string, $pad_type) {}
function chop($str, $character_mask) {}
function strchr($haystack, $needle, $part) {}
function sprintf($format, $arg1, $param3) {}
function printf($format, $arg1, $param3) {}
function vprintf($format, $args) {}
function vsprintf($format, $args) {}
function fprintf($stream, $format, $arg1, $param4) {}
function vfprintf($stream, $format, $args) {}
function sscanf($str, $format, &$param3) {}
function fscanf($stream, $format, &$param3) {}
function parse_url($url, $component) {}
function urlencode($str) {}
function urldecode($str) {}
function rawurlencode($str) {}
function rawurldecode($str) {}
function http_build_query($formdata, $prefix, $arg_separator, $enc_type) {}
function readlink($filename) {}
function linkinfo($filename) {}
function symlink($target, $link) {}
function link($target, $link) {}
function unlink($filename, $context) {}
function exec($command, &$output, &$return_value) {}
function system($command, &$return_value) {}
function escapeshellcmd($command) {}
function escapeshellarg($arg) {}
function passthru($command, &$return_value) {}
function shell_exec($cmd) {}
function proc_open($command, $descriptorspec, &$pipes, $cwd, $env, $other_options) {}
function proc_close($process) {}
function proc_terminate($process, $signal) {}
function proc_get_status($process) {}
function proc_nice($priority) {}
function rand($min, $max) {}
function srand($seed) {}
function getrandmax() {}
function mt_rand($min, $max) {}
function mt_srand($seed) {}
function mt_getrandmax() {}
function getservbyname($service, $protocol) {}
function getservbyport($port, $protocol) {}
function getprotobyname($name) {}
function getprotobynumber($proto) {}
function getmyuid() {}
function getmygid() {}
function getmypid() {}
function getmyinode() {}
function getlastmod() {}
function base64_decode($str, $strict) {}
function base64_encode($str) {}
function convert_uuencode($data) {}
function convert_uudecode($data) {}
function abs($number) {}
function ceil($number) {}
function floor($number) {}
function round($number, $precision, $mode) {}
function sin($number) {}
function cos($number) {}
function tan($number) {}
function asin($number) {}
function acos($number) {}
function atan($number) {}
function atanh($number) {}
function atan2($y, $x) {}
function sinh($number) {}
function cosh($number) {}
function tanh($number) {}
function asinh($number) {}
function acosh($number) {}
function expm1($number) {}
function log1p($number) {}
function pi() {}
function is_finite($val) {}
function is_nan($val) {}
function is_infinite($val) {}
function pow($base, $exponent) {}
function exp($number) {}
function log($number, $base) {}
function log10($number) {}
function sqrt($number) {}
function hypot($num1, $num2) {}
function deg2rad($number) {}
function rad2deg($number) {}
function bindec($binary_number) {}
function hexdec($hexadecimal_number) {}
function octdec($octal_number) {}
function decbin($decimal_number) {}
function decoct($decimal_number) {}
function dechex($decimal_number) {}
function base_convert($number, $frombase, $tobase) {}
function number_format($number, $num_decimal_places, $dec_seperator, $thousands_seperator) {}
function fmod($x, $y) {}
function inet_ntop($in_addr) {}
function inet_pton($ip_address) {}
function ip2long($ip_address) {}
function long2ip($proper_address) {}
function getenv($varname) {}
function putenv($setting) {}
function getopt($options, $opts) {}
function sys_getloadavg() {}
function microtime($get_as_float) {}
function gettimeofday($get_as_float) {}
function getrusage($who) {}
function uniqid($prefix, $more_entropy) {}
function quoted_printable_decode($str) {}
function quoted_printable_encode($str) {}
function convert_cyr_string($str, $from, $to) {}
function get_current_user() {}
function set_time_limit($seconds) {}
function header_register_callback($callback) {}
function get_cfg_var($option_name) {}
function magic_quotes_runtime($new_setting) {}
function set_magic_quotes_runtime($new_setting) {}
function get_magic_quotes_gpc() {}
function get_magic_quotes_runtime() {}
function error_log($message, $message_type, $destination, $extra_headers) {}
function error_get_last() {}
function call_user_func($function_name, $parmeter, $param3) {}
function call_user_func_array($function_name, $parameters) {}
function call_user_method($method_name, &$object, $parameter, $param4) {}
function call_user_method_array($method_name, &$object, $params) {}
function forward_static_call($function_name, $parameter, $param3) {}
function forward_static_call_array($function_name, $parameters) {}
function serialize($var) {}
function unserialize($variable_representation) {}
function var_dump($var, $param2) {}
function var_export($var, $return) {}
function debug_zval_dump($var, $param2) {}
function print_r($var, $return) {}
function memory_get_usage($real_usage) {}
function memory_get_peak_usage($real_usage) {}
function register_shutdown_function($function_name) {}
function register_tick_function($function_name, $arg, $param3) {}
function unregister_tick_function($function_name) {}
function highlight_file($file_name, $return) {}
function show_source($file_name, $return) {}
function highlight_string($string, $return) {}
function php_strip_whitespace($file_name) {}
function ini_get($varname) {}
function ini_get_all($extension) {}
function ini_set($varname, $newvalue) {}
function ini_alter($varname, $newvalue) {}
function ini_restore($varname) {}
function get_include_path() {}
function set_include_path($new_include_path) {}
function restore_include_path() {}
function setcookie($name, $value, $expires, $path, $domain, $secure) {}
function setrawcookie($name, $value, $expires, $path, $domain, $secure) {}
function header($header, $replace, $http_response_code) {}
function header_remove($name) {}
function headers_sent(&$file, &$line) {}
function headers_list() {}
function http_response_code($response_code) {}
function connection_aborted() {}
function connection_status() {}
function ignore_user_abort($value) {}
function parse_ini_file($filename, $process_sections, $scanner_mode) {}
function parse_ini_string($ini_string, $process_sections, $scanner_mode) {}
function is_uploaded_file($path) {}
function move_uploaded_file($path, $new_path) {}
function gethostbyaddr($ip_address) {}
function gethostbyname($hostname) {}
function gethostbynamel($hostname) {}
function gethostname() {}
function dns_check_record($host, $type) {}
function checkdnsrr($host, $type) {}
function dns_get_mx($hostname, &$mxhosts, &$weight) {}
function getmxrr($hostname, &$mxhosts, &$weight) {}
function dns_get_record($hostname, $type, array &$authns, array &$addtl, $raw) {}
function intval($var, $base) {}
function floatval($var) {}
function doubleval($var) {}
function strval($var) {}
function gettype($var) {}
function settype(&$var, $type) {}
function is_null($var) {}
function is_resource($var) {}
function is_bool($var) {}
function is_long($var) {}
function is_float($var) {}
function is_int($var) {}
function is_integer($var) {}
function is_double($var) {}
function is_real($var) {}
function is_numeric($value) {}
function is_string($var) {}
function is_array($var) {}
function is_object($var) {}
function is_scalar($value) {}
function is_callable($var, $syntax_only, &$callable_name) {}
function pclose($fp) {}
function popen($command, $mode) {}
function readfile($filename, $flags, $context) {}
function rewind($fp) {}
function rmdir($dirname, $context) {}
function umask($mask) {}
function fclose($fp) {}
function feof($fp) {}
function fgetc($fp) {}
function fgets($fp, $length) {}
function fgetss($fp, $length, $allowable_tags) {}
function fread($fp, $length) {}
function fopen($filename, $mode, $use_include_path, $context) {}
function fpassthru($fp) {}
function ftruncate($fp, $size) {}
function fstat($fp) {}
function fseek($fp, $offset, $whence) {}
function ftell($fp) {}
function fflush($fp) {}
function fwrite($fp, $str, $length) {}
function fputs($fp, $str, $length) {}
function mkdir($pathname, $mode, $recursive, $context) {}
function rename($old_name, $new_name, $context) {}
function copy($source_file, $destination_file, $context) {}
function tempnam($dir, $prefix) {}
function tmpfile() {}
function file($filename, $flags, $context) {}
function file_get_contents($filename, $flags, $context, $offset, $maxlen) {}
function file_put_contents($filename, $data, $flags, $context) {}
function stream_select(&$read_streams, &$write_streams, &$except_streams, $tv_sec, $tv_usec) {}
function stream_context_create($options, $params) {}
function stream_context_set_params($stream_or_context, $options) {}
function stream_context_get_params($stream_or_context) {}
function stream_context_set_option($stream_or_context, $wrappername, $optionname, $value) {}
function stream_context_get_options($stream_or_context) {}
function stream_context_get_default($options) {}
function stream_context_set_default($options) {}
function stream_filter_prepend($stream, $filtername, $read_write, $filterparams) {}
function stream_filter_append($stream, $filtername, $read_write, $filterparams) {}
function stream_filter_remove($stream_filter) {}
function stream_socket_client($remoteaddress, &$errcode, &$errstring, $timeout, $flags, $context) {}
function stream_socket_server($localaddress, &$errcode, &$errstring, $flags, $context) {}
function stream_socket_accept($serverstream, $timeout, &$peername) {}
function stream_socket_get_name($stream, $want_peer) {}
function stream_socket_recvfrom($stream, $amount, $flags, &$remote_addr) {}
function stream_socket_sendto($stream, $data, $flags, $target_addr) {}
function stream_socket_enable_crypto($stream, $enable, $cryptokind, $sessionstream) {}
function stream_socket_shutdown($stream, $how) {}
function stream_socket_pair($domain, $type, $protocol) {}
function stream_copy_to_stream($source, $dest, $maxlen, $pos) {}
function stream_get_contents($source, $maxlen, $offset) {}
function stream_supports_lock($stream) {}
function fgetcsv($fp, $length, $delimiter, $enclosure, $escape) {}
function fputcsv($fp, $fields, $delimiter, $enclosure) {}
function flock($fp, $operation, &$wouldblock) {}
function get_meta_tags($filename, $use_include_path) {}
function stream_set_read_buffer($fp, $buffer) {}
function stream_set_write_buffer($fp, $buffer) {}
function set_file_buffer($fp, $buffer) {}
function stream_set_chunk_size($fp, $chunk_size) {}
function set_socket_blocking($socket, $mode) {}
function stream_set_blocking($socket, $mode) {}
function socket_set_blocking($socket, $mode) {}
function stream_get_meta_data($fp) {}
function stream_get_line($stream, $maxlen, $ending) {}
function stream_wrapper_register($protocol, $classname, $flags) {}
function stream_register_wrapper($protocol, $classname, $flags) {}
function stream_wrapper_unregister($protocol) {}
function stream_wrapper_restore($protocol) {}
function stream_get_wrappers() {}
function stream_get_transports() {}
function stream_resolve_include_path($filename) {}
function stream_is_local($stream) {}
function get_headers($url, $format) {}
function stream_set_timeout($stream, $seconds, $microseconds) {}
function socket_set_timeout($stream, $seconds, $microseconds) {}
function socket_get_status($fp) {}
function realpath($path) {}
function fnmatch($pattern, $filename, $flags) {}
function fsockopen($hostname, $port, &$errno, &$errstr, $timeout) {}
function pfsockopen($hostname, $port, &$errno, &$errstr, $timeout) {}
function pack($format, $arg1, $param3) {}
function unpack($format, $input) {}
function get_browser($browser_name, $return_array) {}
function crypt($str, $salt) {}
function opendir($path, $context) {}
function closedir($dir_handle) {}
function chdir($directory) {}
function getcwd() {}
function rewinddir($dir_handle) {}
function readdir($dir_handle) {}
function dir($directory, $context) {}
function scandir($dir, $sorting_order, $context) {}
function glob($pattern, $flags) {}
function fileatime($filename) {}
function filectime($filename) {}
function filegroup($filename) {}
function fileinode($filename) {}
function filemtime($filename) {}
function fileowner($filename) {}
function fileperms($filename) {}
function filesize($filename) {}
function filetype($filename) {}
function file_exists($filename) {}
function is_writable($filename) {}
function is_writeable($filename) {}
function is_readable($filename) {}
function is_executable($filename) {}
function is_file($filename) {}
function is_dir($filename) {}
function is_link($filename) {}
function stat($filename) {}
function lstat($filename) {}
function chown($filename, $user) {}
function chgrp($filename, $group) {}
function lchown($filename, $user) {}
function lchgrp($filename, $group) {}
function chmod($filename, $mode) {}
function touch($filename, $time, $atime) {}
function clearstatcache($clear_realpath_cache, $filename) {}
function disk_total_space($path) {}
function disk_free_space($path) {}
function diskfreespace($path) {}
function realpath_cache_size() {}
function realpath_cache_get() {}
function mail($to, $subject, $message, $additional_headers, $additional_parameters) {}
function ezmlm_hash($addr) {}
function openlog($ident, $option, $facility) {}
function syslog($priority, $message) {}
function closelog() {}
function lcg_value() {}
function metaphone($text, $phones) {}
function ob_start($user_function, $chunk_size, $flags) {}
function ob_flush() {}
function ob_clean() {}
function ob_end_flush() {}
function ob_end_clean() {}
function ob_get_flush() {}
function ob_get_clean() {}
function ob_get_length() {}
function ob_get_level() {}
function ob_get_status($full_status) {}
function ob_get_contents() {}
function ob_implicit_flush($flag) {}
function ob_list_handlers() {}
function ksort(&$arg, $sort_flags) {}
function krsort(&$arg, $sort_flags) {}
function natsort(&$arg) {}
function natcasesort(&$arg) {}
function asort(&$arg, $sort_flags) {}
function arsort(&$arg, $sort_flags) {}
function sort(&$arg, $sort_flags) {}
function rsort(&$arg, $sort_flags) {}
function usort(&$arg, $cmp_function) {}
function uasort(&$arg, $cmp_function) {}
function uksort(&$arg, $cmp_function) {}
function shuffle(&$arg) {}
function array_walk(&$input, $funcname, $userdata) {}
function array_walk_recursive(&$input, $funcname, $userdata) {}
function count($var, $mode) {}
function end(&$arg) {}
function prev(&$arg) {}
function next(&$arg) {}
function reset(&$arg) {}
function current(&$arg) {}
function key(&$arg) {}
function min($arg1, $arg2, $param3) {}
function max($arg1, $arg2, $param3) {}
function in_array($needle, $haystack, $strict) {}
function array_search($needle, $haystack, $strict) {}
function extract(&$arg, $extract_type, $prefix) {}
function compact($var_names, $param2) {}
function array_fill($start_key, $num, $val) {}
function array_fill_keys($keys, $val) {}
function range($low, $high, $step) {}
function array_multisort(&$arr1, &$SORT_ASC_or_SORT_DESC, &$SORT_REGULAR_or_SORT_NUMERIC_or_SORT_STRING, &$arr2, &$SORT_ASC_or_SORT_DESC, &$SORT_REGULAR_or_SORT_NUMERIC_or_SORT_STRING) {}
function array_push(&$stack, $var, $param3) {}
function array_pop(&$stack) {}
function array_shift(&$stack) {}
function array_unshift(&$stack, $var, $param3) {}
function array_splice(&$arg, $offset, $length, $replacement) {}
function array_slice($arg, $offset, $length, $preserve_keys) {}
function array_merge($arr1, $arr2, $param3) {}
function array_merge_recursive($arr1, $arr2, $param3) {}
function array_replace($arr1, $arr2, $param3) {}
function array_replace_recursive($arr1, $arr2, $param3) {}
function array_keys($arg, $search_value, $strict) {}
function array_values($arg) {}
function array_count_values($arg) {}
function array_reverse($input, $preserve_keys) {}
function array_reduce($arg, $callback, $initial) {}
function array_pad($arg, $pad_size, $pad_value) {}
function array_flip($arg) {}
function array_change_key_case($input, $case) {}
function array_rand($arg, $num_req) {}
function array_unique($arg) {}
function array_intersect($arr1, $arr2, $param3) {}
function array_intersect_key($arr1, $arr2, $param3) {}
function array_intersect_ukey($arr1, $arr2, $callback_key_compare_func) {}
function array_uintersect($arr1, $arr2, $callback_data_compare_func) {}
function array_intersect_assoc($arr1, $arr2, $param3) {}
function array_uintersect_assoc($arr1, $arr2, $callback_data_compare_func) {}
function array_intersect_uassoc($arr1, $arr2, $callback_key_compare_func) {}
function array_uintersect_uassoc($arr1, $arr2, $callback_data_compare_func, $callback_key_compare_func) {}
function array_diff($arr1, $arr2, $param3) {}
function array_diff_key($arr1, $arr2, $param3) {}
function array_diff_ukey($arr1, $arr2, $callback_key_comp_func) {}
function array_udiff($arr1, $arr2, $callback_data_comp_func) {}
function array_diff_assoc($arr1, $arr2, $param3) {}
function array_udiff_assoc($arr1, $arr2, $callback_key_comp_func) {}
function array_diff_uassoc($arr1, $arr2, $callback_data_comp_func) {}
function array_udiff_uassoc($arr1, $arr2, $callback_data_comp_func, $callback_key_comp_func) {}
function array_sum($arg) {}
function array_product($arg) {}
function array_filter($arg, $callback) {}
function array_map($callback, $arg, $param3) {}
function array_chunk($arg, $size, $preserve_keys) {}
function array_combine($keys, $values) {}
function array_key_exists($key, $search) {}
function pos(&$arg) {}
function sizeof($var, $mode) {}
function key_exists($key, $search) {}
function assert($assertion) {}
function assert_options($what, $value) {}
function version_compare($ver1, $ver2, $oper) {}
function ftok($pathname, $proj) {}
function str_rot13($str) {}
function stream_get_filters() {}
function stream_filter_register($filtername, $classname) {}
function stream_bucket_make_writeable($brigade) {}
function stream_bucket_prepend($brigade, $bucket) {}
function stream_bucket_append($brigade, $bucket) {}
function stream_bucket_new($stream, $buffer) {}
function output_add_rewrite_var($name, $value) {}
function output_reset_rewrite_vars() {}
function sys_get_temp_dir() {}
function dl($extension_filename) {}
class __PHP_Incomplete_Class{
}
class php_user_filter{
  public function filter($in, $out, &$consumed, $closing) {}
  public function onCreate() {}
  public function onClose() {}
}
class Directory{
  public function close($dir_handle) {}
  public function rewind($dir_handle) {}
  public function read($dir_handle) {}
}
