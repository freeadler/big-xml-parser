<?php

namespace Parsec\Xml;
use \Parsec\Exception\ParseException;

class XmlReader
{
  protected $_fileInput;
  protected $_ended;
  public function __construct($filename)
  {
    $this->_fileInput = fopen($filename, 'r');
    $this->_ended = false;
  }

  public function readData()
  {
    if($this->_ended) {
      return false;
    }
    $buffer = stream_get_line($this->_fileInput, 65535, "");
    if(!$buffer) {
      fclose($this->_fileInput);
      $this->_ended = true;
    }
    return $buffer;
  }
}