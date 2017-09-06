<?php

namespace Parsec\Xml;
use \Parsec\Exception\ParseException;

class TagParser
{
  protected $_tagName;
  protected $_tagFull;
  protected $_endOffset;

  public function __construct($buffer, $tagName, $offset = 0)
  {
    $tagStart = strpos($buffer, "<$tagName>", $offset);
    if($tagStart === false) {
      throw new ParseException("$tagName start is not found");
    }
    $tagEnd = strpos($buffer, "</$tagName>", $tagStart);
    if($tagEnd === false) {
      throw new ParseException("$tagName end is not found");
    }
    $tagEnd += strlen($tagName) + 3;
    $this->_tagFull = substr($buffer, $tagStart, $tagEnd - $tagStart);
    $this->_tagName = $tagName;
    $this->_endOffset = $tagEnd;
  }

  public function getEndOffset()
  {
    return $this->_endOffset;
  }

  public function getFullXml()
  {
    return $this->_tagFull;
  }

  public function getValue()
  {
    $taglen = strlen($this->_tagName) + 2;
    return substr($this->_tagFull, $taglen, 0-($taglen + 1));
  }
}