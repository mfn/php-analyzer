<?php
# Generated by mfn/php-reflection-gen 0.0.1
# PHP version 5.4.30, extension mysqli 0.1
function mysqli_affected_rows($link) {}
function mysqli_autocommit($link, $mode) {}
function mysqli_change_user($link, $user, $password, $database) {}
function mysqli_character_set_name($link) {}
function mysqli_close($link) {}
function mysqli_commit($link) {}
function mysqli_connect($host, $user, $password, $database, $port, $socket) {}
function mysqli_connect_errno() {}
function mysqli_connect_error() {}
function mysqli_data_seek($result, $offset) {}
function mysqli_dump_debug_info($link) {}
function mysqli_debug($debug_options) {}
function mysqli_errno($link) {}
function mysqli_error($link) {}
function mysqli_error_list($link) {}
function mysqli_stmt_execute($stmt) {}
function mysqli_execute($stmt) {}
function mysqli_fetch_field($result) {}
function mysqli_fetch_fields($result) {}
function mysqli_fetch_field_direct($result, $field_nr) {}
function mysqli_fetch_lengths($result) {}
function mysqli_fetch_all($result) {}
function mysqli_fetch_array($result, $result_type) {}
function mysqli_fetch_assoc($result) {}
function mysqli_fetch_object($result, $class_name, array $params) {}
function mysqli_fetch_row($result) {}
function mysqli_field_count($link) {}
function mysqli_field_seek($result, $field_nr) {}
function mysqli_field_tell($result) {}
function mysqli_free_result($result) {}
function mysqli_get_connection_stats($link) {}
function mysqli_get_client_stats() {}
function mysqli_get_charset($link) {}
function mysqli_get_client_info($link) {}
function mysqli_get_client_version($link) {}
function mysqli_get_host_info($link) {}
function mysqli_get_proto_info($link) {}
function mysqli_get_server_info($link) {}
function mysqli_get_server_version($link) {}
function mysqli_get_warnings($link) {}
function mysqli_init() {}
function mysqli_info($link) {}
function mysqli_insert_id($link) {}
function mysqli_kill($link, $connection_id) {}
function mysqli_more_results($link) {}
function mysqli_multi_query($link, $query) {}
function mysqli_next_result($link) {}
function mysqli_num_fields($result) {}
function mysqli_num_rows($result) {}
function mysqli_options($link, $option, $value) {}
function mysqli_ping($link) {}
function mysqli_poll(array &$read, array &$write, array &$error, $sec, $usec) {}
function mysqli_prepare($link, $query) {}
function mysqli_report($flags) {}
function mysqli_query($link, $query) {}
function mysqli_real_connect($link, $host, $user, $password, $database, $port, $socket, $flags) {}
function mysqli_real_escape_string($link, $string_to_escape) {}
function mysqli_real_query($link, $query) {}
function mysqli_reap_async_query($link) {}
function mysqli_rollback($link) {}
function mysqli_select_db($link, $database) {}
function mysqli_set_charset($link, $charset) {}
function mysqli_stmt_affected_rows($stmt) {}
function mysqli_stmt_attr_get($stmt, $attribute) {}
function mysqli_stmt_attr_set($stmt, $attribute, $value) {}
function mysqli_stmt_bind_param($stmt, $types) {}
function mysqli_stmt_bind_result($stmt) {}
function mysqli_stmt_close($stmt) {}
function mysqli_stmt_data_seek($stmt, $offset) {}
function mysqli_stmt_errno($stmt) {}
function mysqli_stmt_error($stmt) {}
function mysqli_stmt_error_list($stmt) {}
function mysqli_stmt_fetch($stmt) {}
function mysqli_stmt_field_count($stmt) {}
function mysqli_stmt_free_result($stmt) {}
function mysqli_stmt_get_result($stmt) {}
function mysqli_stmt_get_warnings($stmt) {}
function mysqli_stmt_init($link) {}
function mysqli_stmt_insert_id($stmt) {}
function mysqli_stmt_more_results($stmt) {}
function mysqli_stmt_next_result($stmt) {}
function mysqli_stmt_num_rows($stmt) {}
function mysqli_stmt_param_count($stmt) {}
function mysqli_stmt_prepare($stmt, $query) {}
function mysqli_stmt_reset($stmt) {}
function mysqli_stmt_result_metadata($stmt) {}
function mysqli_stmt_send_long_data($stmt, $param_nr, $data) {}
function mysqli_stmt_store_result($stmt) {}
function mysqli_stmt_sqlstate($stmt) {}
function mysqli_sqlstate($link) {}
function mysqli_ssl_set($link, $key, $cert, $certificate_authority, $certificate_authority_path, $cipher) {}
function mysqli_stat($link) {}
function mysqli_store_result($link) {}
function mysqli_thread_id($link) {}
function mysqli_thread_safe() {}
function mysqli_use_result($link) {}
function mysqli_warning_count($link) {}
function mysqli_refresh($link, $options) {}
function mysqli_escape_string($link, $query) {}
function mysqli_set_opt() {}
class mysqli_sql_exception extends RuntimeException{
  final private function __clone() {}
  public function __construct($message, $code, $previous) {}
  final public function getMessage() {}
  final public function getCode() {}
  final public function getFile() {}
  final public function getLine() {}
  final public function getTrace() {}
  final public function getPrevious() {}
  final public function getTraceAsString() {}
  public function __toString() {}
}
class mysqli_driver{
}
class mysqli{
  public function autocommit($mode) {}
  public function change_user($user, $password, $database) {}
  public function character_set_name() {}
  public function close() {}
  public function commit() {}
  public function connect($host, $user, $password, $database, $port, $socket) {}
  public function dump_debug_info() {}
  public function debug($debug_options) {}
  public function get_charset() {}
  public function get_client_info() {}
  public function get_connection_stats() {}
  public function get_server_info() {}
  public function get_warnings() {}
  public function init() {}
  public function kill($connection_id) {}
  public function multi_query($query) {}
  public function mysqli($host, $user, $password, $database, $port, $socket) {}
  public function more_results() {}
  public function next_result() {}
  public function options($option, $value) {}
  public function ping() {}
  static public function poll(array &$read, array &$write, array &$error, $sec, $usec) {}
  public function prepare($query) {}
  public function query($query) {}
  public function real_connect($host, $user, $password, $database, $port, $socket, $flags) {}
  public function real_escape_string($string_to_escape) {}
  public function reap_async_query() {}
  public function escape_string($string_to_escape) {}
  public function real_query($query) {}
  public function rollback() {}
  public function select_db($database) {}
  public function set_charset($charset) {}
  public function set_opt($option, $value) {}
  public function ssl_set($key, $cert, $certificate_authority, $certificate_authority_path, $cipher) {}
  public function stat() {}
  public function stmt_init() {}
  public function store_result() {}
  public function thread_safe() {}
  public function use_result() {}
  public function refresh($options) {}
}
class mysqli_warning{
  protected function __construct() {}
  public function next() {}
}
class mysqli_result implements Traversable{
  public function __construct() {}
  public function close() {}
  public function free() {}
  public function data_seek($offset) {}
  public function fetch_field() {}
  public function fetch_fields() {}
  public function fetch_field_direct($field_nr) {}
  public function fetch_all() {}
  public function fetch_array($result_type) {}
  public function fetch_assoc() {}
  public function fetch_object($class_name, array $params) {}
  public function fetch_row() {}
  public function field_seek($field_nr) {}
  public function free_result() {}
}
class mysqli_stmt{
  public function __construct() {}
  public function attr_get($attribute) {}
  public function attr_set($attribute, $value) {}
  public function bind_param($types) {}
  public function bind_result() {}
  public function close() {}
  public function data_seek($offset) {}
  public function execute() {}
  public function fetch() {}
  public function get_warnings() {}
  public function result_metadata() {}
  public function more_results() {}
  public function next_result() {}
  public function num_rows() {}
  public function send_long_data($param_nr, $data) {}
  public function free_result() {}
  public function reset() {}
  public function prepare($query) {}
  public function store_result() {}
  public function get_result() {}
}
