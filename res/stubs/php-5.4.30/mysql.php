<?php
# Generated by mfn/php-reflection-gen 0.0.1
# PHP version 5.4.30, extension mysql 1.0
function mysql_connect($hostname, $username, $password, $new, $flags) {}
function mysql_pconnect($hostname, $username, $password, $flags) {}
function mysql_close($link_identifier) {}
function mysql_select_db($database_name, $link_identifier) {}
function mysql_query($query, $link_identifier) {}
function mysql_unbuffered_query($query, $link_identifier) {}
function mysql_db_query($database_name, $query, $link_identifier) {}
function mysql_list_dbs($link_identifier) {}
function mysql_list_tables($database_name, $link_identifier) {}
function mysql_list_fields($database_name, $table_name, $link_identifier) {}
function mysql_list_processes($link_identifier) {}
function mysql_error($link_identifier) {}
function mysql_errno($link_identifier) {}
function mysql_affected_rows($link_identifier) {}
function mysql_insert_id($link_identifier) {}
function mysql_result($result, $row, $field) {}
function mysql_num_rows($result) {}
function mysql_num_fields($result) {}
function mysql_fetch_row($result) {}
function mysql_fetch_array($result, $result_type) {}
function mysql_fetch_assoc($result) {}
function mysql_fetch_object($result, $class_name, $ctor_params) {}
function mysql_data_seek($result, $row_number) {}
function mysql_fetch_lengths($result) {}
function mysql_fetch_field($result, $field_offset) {}
function mysql_field_seek($result, $field_offset) {}
function mysql_free_result($result) {}
function mysql_field_name($result, $field_index) {}
function mysql_field_table($result, $field_offset) {}
function mysql_field_len($result, $field_offset) {}
function mysql_field_type($result, $field_offset) {}
function mysql_field_flags($result, $field_offset) {}
function mysql_escape_string($string) {}
function mysql_real_escape_string($string, $link_identifier) {}
function mysql_stat($link_identifier) {}
function mysql_thread_id($link_identifier) {}
function mysql_client_encoding($link_identifier) {}
function mysql_ping($link_identifier) {}
function mysql_get_client_info() {}
function mysql_get_host_info($link_identifier) {}
function mysql_get_proto_info($link_identifier) {}
function mysql_get_server_info($link_identifier) {}
function mysql_info($link_identifier) {}
function mysql_set_charset($charset_name, $link_identifier) {}
function mysql($database_name, $query, $link_identifier) {}
function mysql_fieldname($result, $field_index) {}
function mysql_fieldtable($result, $field_offset) {}
function mysql_fieldlen($result, $field_offset) {}
function mysql_fieldtype($result, $field_offset) {}
function mysql_fieldflags($result, $field_offset) {}
function mysql_selectdb($database_name, $link_identifier) {}
function mysql_freeresult($result) {}
function mysql_numfields($result) {}
function mysql_numrows($result) {}
function mysql_listdbs($link_identifier) {}
function mysql_listtables($database_name, $link_identifier) {}
function mysql_listfields($database_name, $table_name, $link_identifier) {}
function mysql_db_name($result, $row, $field) {}
function mysql_dbname($result, $row, $field) {}
function mysql_tablename($result, $row, $field) {}
function mysql_table_name($result, $row, $field) {}
