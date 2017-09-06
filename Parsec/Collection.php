<?php

namespace Parsec;

abstract class Collection implements \Iterator, \Countable, \ArrayAccess
{
  private   $_innerArray = [];

  abstract protected function _getItemType();

  private function _checkType($value)
  {
    $t = $this->_getItemType();
    $c = $value instanceof $t;
    return $c;
  }

  protected function _offsetGenerator($offset, $value)
  {
    if (!is_null($offset)) {
      return $offset;
    }
    return $this->count();
  }

  public function offsetSet($offset, $value) 
  {
    if($this->_checkType($value) === false) {
      throw new Exception("Value type doesn't match collections type");
    }
    $generatedOffset = $this->_offsetGenerator($offset, $value);
    $this->_innerArray[$offset] = $value;
  }

  public function offsetExists($offset) 
  {
    return isset($this->_innerArray[$offset]);
  }

  public function offsetUnset($offset) 
  {
    unset($this->_innerArray[$offset]);
  }

  public function offsetGet($offset) 
  {
    return isset($this->_innerArray[$offset]) ? $this->_innerArray[$offset] : null;
  }

  function rewind() 
  {
    return reset($this->_innerArray);
  }
  function current() 
  {
    return current($this->_innerArray);
  }
  function key() 
  {
    return key($this->_innerArray);
  }
  function next() 
  {
    return next($this->_innerArray);
  }
  function valid() 
  {
    return key($this->_innerArray) !== null;
  }

  public function count() 
  {
    return count($this->_innerArray);
  }
}