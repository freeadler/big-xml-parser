<?php

namespace Parsec\Argument;
use \Parsec\Argument;
use \Parsec\Exception\ArgumentException;

class Input extends Argument
{
  public function getName()
  {
    return "input";
  }

  protected function _checkValue($value)
  {
    if(empty($value)) {
      throw new ArgumentException("Input filename can't be empty");
    }
    if(!file_exists($value)) {
      throw new ArgumentException("Input file does not exist");
    }
    return $value;
  }
}