<?php

namespace Parsec\Argument;
use \Parsec\Argument;
use \Parsec\Exception\ArgumentException;

abstract class Age extends Argument
{
  protected function _checkValue($value)
  {
    $value = intval($value);

    if( $value <= 0) {
      throw new ArgumentException("Age can't be less or equal to zero");
    }
    return $value;
  }
}