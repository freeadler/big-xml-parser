<?php

namespace Parsec\Xml;
use \Parsec\Exception\ParseException;

class XmlWriter
{
  protected $_fileOutput;
  public function __construct($filename)
  {
    $this->_fileOutput = fopen($filename, 'w');
  }

  public function writeData($data)
  {
    fwrite($this->_fileOutput, $data);
  }

  public function close()
  {
    fclose($this->_fileOutput);
  }
}