<?php

namespace Parsec\Xml;

class UserParser extends TagParser
{
  protected $_tagName = "user";
  protected $_age = null;

  public function __construct($buffer, $offset = 0)
  {
    parent::__construct($buffer, $this->_tagName, $offset);
  }

  public function getAge()
  {
    if($this->_age === null) {
      $age = new TagParser($this->getFullXml(), "age");
      $this->_age = intval($age->getValue());
    }
    return $this->_age;
  }
}