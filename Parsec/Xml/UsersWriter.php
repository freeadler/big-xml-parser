<?php

namespace Parsec\Xml;
use \Parsec\Exception\ParseException;

class UsersWriter extends XmlWriter
{
  public function __construct($filename)
  {
    parent::__construct($filename);
    $this->writeData("<users>");
  }

  public function writeUser($user)
  {
    $this->writeData($user->getFullXml());
  }

  public function close()
  {
    $this->writeData("\n</users>");
    parent::close();
  }
}