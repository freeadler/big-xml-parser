<?php

namespace Parsec\Argument;
use \Parsec\Argument;
use \Parsec\Exception\ArgumentException;

class Output extends Argument
{
  public function getName()
  {
    return "output";
  }

  protected function _checkValue($value)
  {
    if(empty($value)) {
      throw new ArgumentException("Output filename can't be empty");
    }
    return $value;
  }
}