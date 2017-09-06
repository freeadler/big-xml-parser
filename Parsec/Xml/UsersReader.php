<?php

namespace Parsec\Xml;
use \Parsec\Exception\ParseException;

class UsersReader extends XmlReader
{
  protected $_buffer;
  protected $_userOffset = 0;
  protected $_bufferLen;

  public function getNextUser()
  {
    $user = null;
    if($this->_ended) {
      return null;
    }

    try {
      $user = new UserParser($this->_buffer, $this->_userOffset);
      $this->_userOffset = $user->getEndOffset();
    } catch(ParseException $e) {
      $endBuffer = substr($this->_buffer, $this->_userOffset);
      $this->_buffer = $endBuffer . $this->readData();
      if(!$this->_buffer) {
        return null;
      }
      $this->_userOffset = 0;
      $this->_bufferLen = strlen($this->_buffer);
      return $this->getNextUser();
    }

    return $user;
  }
}