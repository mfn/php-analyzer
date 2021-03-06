<?php
# Generated by mfn/php-reflection-gen 0.0.1
# PHP version 5.4.30, extension Phar 2.0.1
class PharException extends Exception{
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
class Phar extends RecursiveDirectoryIterator implements RecursiveIterator, SeekableIterator, Traversable, Iterator, Countable, ArrayAccess{
  public function __construct($filename, $flags, $alias, $fileformat) {}
  public function __destruct() {}
  public function addEmptyDir($dirname) {}
  public function addFile($filename, $localname) {}
  public function addFromString($localname, $contents) {}
  public function buildFromDirectory($base_dir, $regex) {}
  public function buildFromIterator($iterator, $base_directory) {}
  public function compressFiles($compression_type) {}
  public function decompressFiles() {}
  public function compress($compression_type, $file_ext) {}
  public function decompress($file_ext) {}
  public function convertToExecutable($format, $compression_type, $file_ext) {}
  public function convertToData($format, $compression_type, $file_ext) {}
  public function copy($newfile, $oldfile) {}
  public function count() {}
  public function delete($entry) {}
  public function delMetadata() {}
  public function extractTo($pathto, $files, $overwrite) {}
  public function getAlias() {}
  public function getPath() {}
  public function getMetadata() {}
  public function getModified() {}
  public function getSignature() {}
  public function getStub() {}
  public function getVersion() {}
  public function hasMetadata() {}
  public function isBuffering() {}
  public function isCompressed() {}
  public function isFileFormat($fileformat) {}
  public function isWritable() {}
  public function offsetExists($entry) {}
  public function offsetGet($entry) {}
  public function offsetSet($entry, $value) {}
  public function offsetUnset($entry) {}
  public function setAlias($alias) {}
  public function setDefaultStub($index, $webindex) {}
  public function setMetadata($metadata) {}
  public function setSignatureAlgorithm($algorithm, $privatekey) {}
  public function setStub($newstub, $maxlen) {}
  public function startBuffering() {}
  public function stopBuffering() {}
  final static public function apiVersion() {}
  final static public function canCompress($method) {}
  final static public function canWrite() {}
  final static public function createDefaultStub($index, $webindex) {}
  final static public function getSupportedCompression() {}
  final static public function getSupportedSignatures() {}
  final static public function interceptFileFuncs() {}
  final static public function isValidPharFilename($filename, $executable) {}
  final static public function loadPhar($filename, $alias) {}
  final static public function mapPhar($alias, $offset) {}
  final static public function running($retphar) {}
  final static public function mount($inphar, $externalfile) {}
  final static public function mungServer($munglist) {}
  final static public function unlinkArchive($archive) {}
  final static public function webPhar($alias, $index, $f404, $mimetypes, $rewrites) {}
  public function hasChildren($allow_links) {}
  public function getChildren() {}
  public function getSubPath() {}
  public function getSubPathname() {}
  public function rewind() {}
  public function next() {}
  public function key() {}
  public function current() {}
  public function getFlags() {}
  public function setFlags($flags) {}
  public function getFilename() {}
  public function getExtension() {}
  public function getBasename($suffix) {}
  public function isDot() {}
  public function valid() {}
  public function seek($position) {}
  public function __toString() {}
  public function getPathname() {}
  public function getPerms() {}
  public function getInode() {}
  public function getSize() {}
  public function getOwner() {}
  public function getGroup() {}
  public function getATime() {}
  public function getMTime() {}
  public function getCTime() {}
  public function getType() {}
  public function isReadable() {}
  public function isExecutable() {}
  public function isFile() {}
  public function isDir() {}
  public function isLink() {}
  public function getLinkTarget() {}
  public function getRealPath() {}
  public function getFileInfo($class_name) {}
  public function getPathInfo($class_name) {}
  public function openFile($open_mode, $use_include_path, $context) {}
  public function setFileClass($class_name) {}
  public function setInfoClass($class_name) {}
  final public function _bad_state_ex() {}
}
class PharData extends RecursiveDirectoryIterator implements RecursiveIterator, SeekableIterator, Traversable, Iterator, Countable, ArrayAccess{
  public function __construct($filename, $flags, $alias, $fileformat) {}
  public function __destruct() {}
  public function addEmptyDir($dirname) {}
  public function addFile($filename, $localname) {}
  public function addFromString($localname, $contents) {}
  public function buildFromDirectory($base_dir, $regex) {}
  public function buildFromIterator($iterator, $base_directory) {}
  public function compressFiles($compression_type) {}
  public function decompressFiles() {}
  public function compress($compression_type, $file_ext) {}
  public function decompress($file_ext) {}
  public function convertToExecutable($format, $compression_type, $file_ext) {}
  public function convertToData($format, $compression_type, $file_ext) {}
  public function copy($newfile, $oldfile) {}
  public function count() {}
  public function delete($entry) {}
  public function delMetadata() {}
  public function extractTo($pathto, $files, $overwrite) {}
  public function getAlias() {}
  public function getPath() {}
  public function getMetadata() {}
  public function getModified() {}
  public function getSignature() {}
  public function getStub() {}
  public function getVersion() {}
  public function hasMetadata() {}
  public function isBuffering() {}
  public function isCompressed() {}
  public function isFileFormat($fileformat) {}
  public function isWritable() {}
  public function offsetExists($entry) {}
  public function offsetGet($entry) {}
  public function offsetSet($entry, $value) {}
  public function offsetUnset($entry) {}
  public function setAlias($alias) {}
  public function setDefaultStub($index, $webindex) {}
  public function setMetadata($metadata) {}
  public function setSignatureAlgorithm($algorithm, $privatekey) {}
  public function setStub($newstub, $maxlen) {}
  public function startBuffering() {}
  public function stopBuffering() {}
  final static public function apiVersion() {}
  final static public function canCompress($method) {}
  final static public function canWrite() {}
  final static public function createDefaultStub($index, $webindex) {}
  final static public function getSupportedCompression() {}
  final static public function getSupportedSignatures() {}
  final static public function interceptFileFuncs() {}
  final static public function isValidPharFilename($filename, $executable) {}
  final static public function loadPhar($filename, $alias) {}
  final static public function mapPhar($alias, $offset) {}
  final static public function running($retphar) {}
  final static public function mount($inphar, $externalfile) {}
  final static public function mungServer($munglist) {}
  final static public function unlinkArchive($archive) {}
  final static public function webPhar($alias, $index, $f404, $mimetypes, $rewrites) {}
  public function hasChildren($allow_links) {}
  public function getChildren() {}
  public function getSubPath() {}
  public function getSubPathname() {}
  public function rewind() {}
  public function next() {}
  public function key() {}
  public function current() {}
  public function getFlags() {}
  public function setFlags($flags) {}
  public function getFilename() {}
  public function getExtension() {}
  public function getBasename($suffix) {}
  public function isDot() {}
  public function valid() {}
  public function seek($position) {}
  public function __toString() {}
  public function getPathname() {}
  public function getPerms() {}
  public function getInode() {}
  public function getSize() {}
  public function getOwner() {}
  public function getGroup() {}
  public function getATime() {}
  public function getMTime() {}
  public function getCTime() {}
  public function getType() {}
  public function isReadable() {}
  public function isExecutable() {}
  public function isFile() {}
  public function isDir() {}
  public function isLink() {}
  public function getLinkTarget() {}
  public function getRealPath() {}
  public function getFileInfo($class_name) {}
  public function getPathInfo($class_name) {}
  public function openFile($open_mode, $use_include_path, $context) {}
  public function setFileClass($class_name) {}
  public function setInfoClass($class_name) {}
  final public function _bad_state_ex() {}
}
class PharFileInfo extends SplFileInfo{
  public function __construct($filename) {}
  public function __destruct() {}
  public function chmod($perms) {}
  public function compress($compression_type) {}
  public function decompress() {}
  public function delMetadata() {}
  public function getCompressedSize() {}
  public function getCRC32() {}
  public function getContent() {}
  public function getMetadata() {}
  public function getPharFlags() {}
  public function hasMetadata() {}
  public function isCompressed($compression_type) {}
  public function isCRCChecked() {}
  public function setMetadata($metadata) {}
  public function getPath() {}
  public function getFilename() {}
  public function getExtension() {}
  public function getBasename($suffix) {}
  public function getPathname() {}
  public function getPerms() {}
  public function getInode() {}
  public function getSize() {}
  public function getOwner() {}
  public function getGroup() {}
  public function getATime() {}
  public function getMTime() {}
  public function getCTime() {}
  public function getType() {}
  public function isWritable() {}
  public function isReadable() {}
  public function isExecutable() {}
  public function isFile() {}
  public function isDir() {}
  public function isLink() {}
  public function getLinkTarget() {}
  public function getRealPath() {}
  public function getFileInfo($class_name) {}
  public function getPathInfo($class_name) {}
  public function openFile($open_mode, $use_include_path, $context) {}
  public function setFileClass($class_name) {}
  public function setInfoClass($class_name) {}
  final public function _bad_state_ex() {}
  public function __toString() {}
}
