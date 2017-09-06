<?php

namespace Parsec;

abstract class Argument
{
  private   $_required;
  protected $_value = null;

  abstract public function getName();

  public function __construct($required = false)
  {
    $this->_required = $required;
  }

  public function setValue($value)
  {
    $this->_value = $this->_checkValue($value);
  }

  public function getArgumentName()
  {
    return static::getName() . ':' . ($this->_required ? '' : ':');
  }

  protected function _checkValue($value)
  {
    return $value;
  }

  public function getValue()
  {
    return $this->_value;
  }
}