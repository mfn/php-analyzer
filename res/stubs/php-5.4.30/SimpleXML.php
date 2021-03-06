<?php
# Generated by mfn/php-reflection-gen 0.0.1
# PHP version 5.4.30, extension SimpleXML 0.1
function simplexml_load_file($filename, $class_name, $options, $ns, $is_prefix) {}
function simplexml_load_string($data, $class_name, $options, $ns, $is_prefix) {}
function simplexml_import_dom($node, $class_name) {}
class SimpleXMLElement implements Traversable{
  final public function __construct($data, $options, $data_is_url, $ns, $is_prefix) {}
  public function asXML($filename) {}
  public function saveXML($filename) {}
  public function xpath($path) {}
  public function registerXPathNamespace($prefix, $ns) {}
  public function attributes($ns, $is_prefix) {}
  public function children($ns, $is_prefix) {}
  public function getNamespaces($recursve) {}
  public function getDocNamespaces($recursve, $from_root) {}
  public function getName() {}
  public function addChild($name, $value, $ns) {}
  public function addAttribute($name, $value, $ns) {}
  public function __toString() {}
  public function count() {}
}
class SimpleXMLIterator extends SimpleXMLElement implements Traversable, RecursiveIterator, Iterator, Countable{
  public function rewind() {}
  public function valid() {}
  public function current() {}
  public function key() {}
  public function next() {}
  public function hasChildren() {}
  public function getChildren() {}
  final public function __construct($data, $options, $data_is_url, $ns, $is_prefix) {}
  public function asXML($filename) {}
  public function saveXML($filename) {}
  public function xpath($path) {}
  public function registerXPathNamespace($prefix, $ns) {}
  public function attributes($ns, $is_prefix) {}
  public function children($ns, $is_prefix) {}
  public function getNamespaces($recursve) {}
  public function getDocNamespaces($recursve, $from_root) {}
  public function getName() {}
  public function addChild($name, $value, $ns) {}
  public function addAttribute($name, $value, $ns) {}
  public function __toString() {}
  public function count() {}
}
