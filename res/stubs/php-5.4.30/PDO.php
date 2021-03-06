<?php
# Generated by mfn/php-reflection-gen 0.0.1
# PHP version 5.4.30, extension PDO 1.0.4dev
function pdo_drivers() {}
class PDOException extends RuntimeException{
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
class PDO{
  public function __construct($dsn, $username, $passwd, $options) {}
  public function prepare($statement, $options) {}
  public function beginTransaction() {}
  public function commit() {}
  public function rollBack() {}
  public function inTransaction() {}
  public function setAttribute($attribute, $value) {}
  public function exec($query) {}
  public function query() {}
  public function lastInsertId($seqname) {}
  public function errorCode() {}
  public function errorInfo() {}
  public function getAttribute($attribute) {}
  public function quote($string, $paramtype) {}
  final public function __wakeup() {}
  final public function __sleep() {}
  static public function getAvailableDrivers() {}
}
class PDOStatement implements Traversable{
  public function execute($bound_input_params) {}
  public function fetch($how, $orientation, $offset) {}
  public function bindParam($paramno, &$param, $type, $maxlen, $driverdata) {}
  public function bindColumn($column, &$param, $type, $maxlen, $driverdata) {}
  public function bindValue($paramno, $param, $type) {}
  public function rowCount() {}
  public function fetchColumn($column_number) {}
  public function fetchAll($how, $class_name, $ctor_args) {}
  public function fetchObject($class_name, $ctor_args) {}
  public function errorCode() {}
  public function errorInfo() {}
  public function setAttribute($attribute, $value) {}
  public function getAttribute($attribute) {}
  public function columnCount() {}
  public function getColumnMeta($column) {}
  public function setFetchMode($mode, $params) {}
  public function nextRowset() {}
  public function closeCursor() {}
  public function debugDumpParams() {}
  final public function __wakeup() {}
  final public function __sleep() {}
}
class PDORow{
}
