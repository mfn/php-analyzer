<?php
# Generated by mfn/php-reflection-gen 0.0.1
# PHP version 5.4.30, extension date 5.4.30
function strtotime($time, $now) {}
function date($format, $timestamp) {}
function idate($format, $timestamp) {}
function gmdate($format, $timestamp) {}
function mktime($hour, $min, $sec, $mon, $day, $year) {}
function gmmktime($hour, $min, $sec, $mon, $day, $year) {}
function checkdate($month, $day, $year) {}
function strftime($format, $timestamp) {}
function gmstrftime($format, $timestamp) {}
function time() {}
function localtime($timestamp, $associative_array) {}
function getdate($timestamp) {}
function date_create($time, $object) {}
function date_create_from_format($format, $time, $object) {}
function date_parse($date) {}
function date_parse_from_format($format, $date) {}
function date_get_last_errors() {}
function date_format($object, $format) {}
function date_modify($object, $modify) {}
function date_add($object, $interval) {}
function date_sub($object, $interval) {}
function date_timezone_get($object) {}
function date_timezone_set($object, $timezone) {}
function date_offset_get($object) {}
function date_diff($object, $object2, $absolute) {}
function date_time_set($object, $hour, $minute, $second) {}
function date_date_set($object, $year, $month, $day) {}
function date_isodate_set($object, $year, $week, $day) {}
function date_timestamp_set($object, $unixtimestamp) {}
function date_timestamp_get($object) {}
function timezone_open($timezone) {}
function timezone_name_get($object) {}
function timezone_name_from_abbr($abbr, $gmtoffset, $isdst) {}
function timezone_offset_get($object, $datetime) {}
function timezone_transitions_get($object, $timestamp_begin, $timestamp_end) {}
function timezone_location_get($object) {}
function timezone_identifiers_list($what, $country) {}
function timezone_abbreviations_list() {}
function timezone_version_get() {}
function date_interval_create_from_date_string($time) {}
function date_interval_format($object, $format) {}
function date_default_timezone_set($timezone_identifier) {}
function date_default_timezone_get() {}
function date_sunrise($time, $format, $latitude, $longitude, $zenith, $gmt_offset) {}
function date_sunset($time, $format, $latitude, $longitude, $zenith, $gmt_offset) {}
function date_sun_info($time, $latitude, $longitude) {}
class DateTime{
  public function __construct($time, $object) {}
  public function __wakeup() {}
  static public function __set_state() {}
  static public function createFromFormat($format, $time, $object) {}
  static public function getLastErrors() {}
  public function format($format) {}
  public function modify($modify) {}
  public function add($interval) {}
  public function sub($interval) {}
  public function getTimezone() {}
  public function setTimezone($timezone) {}
  public function getOffset() {}
  public function setTime($hour, $minute, $second) {}
  public function setDate($year, $month, $day) {}
  public function setISODate($year, $week, $day) {}
  public function setTimestamp($unixtimestamp) {}
  public function getTimestamp() {}
  public function diff($object, $absolute) {}
}
class DateTimeZone{
  public function __construct($timezone) {}
  public function getName() {}
  public function getOffset($datetime) {}
  public function getTransitions($timestamp_begin, $timestamp_end) {}
  public function getLocation() {}
  static public function listAbbreviations() {}
  static public function listIdentifiers($what, $country) {}
}
class DateInterval{
  public function __construct($interval_spec) {}
  public function __wakeup() {}
  static public function __set_state() {}
  public function format($format) {}
  static public function createFromDateString($time) {}
}
class DatePeriod implements Traversable{
  public function __construct($start, $interval, $end) {}
  public function __wakeup() {}
  static public function __set_state() {}
}
