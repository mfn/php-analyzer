<?php
# Generated by mfn/php-reflection-gen 0.0.1
# PHP version 5.4.30, extension tidy 2.0
function tidy_getopt($option) {}
function tidy_parse_string($input, $config_options, $encoding) {}
function tidy_parse_file($file, $config_options, $encoding, $use_include_path) {}
function tidy_get_output() {}
function tidy_get_error_buffer() {}
function tidy_clean_repair() {}
function tidy_repair_string($data, $config_file, $encoding) {}
function tidy_repair_file($filename, $config_file, $encoding, $use_include_path) {}
function tidy_diagnose() {}
function tidy_get_release() {}
function tidy_get_config() {}
function tidy_get_status() {}
function tidy_get_html_ver() {}
function tidy_is_xhtml() {}
function tidy_is_xml() {}
function tidy_error_count() {}
function tidy_warning_count() {}
function tidy_access_count() {}
function tidy_config_count() {}
function tidy_get_opt_doc($resource, $optname) {}
function tidy_get_root() {}
function tidy_get_head() {}
function tidy_get_html() {}
function tidy_get_body($tidy) {}
class tidy{
  public function getOpt() {}
  public function cleanRepair() {}
  public function parseFile() {}
  public function parseString() {}
  public function repairString() {}
  public function repairFile() {}
  public function diagnose() {}
  public function getRelease() {}
  public function getConfig() {}
  public function getStatus() {}
  public function getHtmlVer() {}
  public function getOptDoc() {}
  public function isXhtml() {}
  public function isXml() {}
  public function root() {}
  public function head() {}
  public function html() {}
  public function body() {}
  public function __construct() {}
}
class tidyNode{
  public function hasChildren() {}
  public function hasSiblings() {}
  public function isComment() {}
  public function isHtml() {}
  public function isText() {}
  public function isJste() {}
  public function isAsp() {}
  public function isPhp() {}
  public function getParent() {}
  private function __construct() {}
}
