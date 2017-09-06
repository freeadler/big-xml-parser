<?php

namespace Parsec\Collection;
use \Parsec\Collection;

class ArgumentsCollection extends Collection
{
  protected function _getItemType()
  {
    return "\Parsec\Argument";
  }

  protected function _offsetGenerator($offset, $value)
  {
    return (new \ReflectionClass($value))->getShortName();;
  }

  public function addArgument($argument)
  {
    if($this->offsetExists((new \ReflectionClass($argument))->getShortName())) {
      throw new Exception("This argument is already in collection");
    }
    $this[(new \ReflectionClass($argument))->getShortName()] = $argument;
  }

  public function __get($name)
  {
    if($this->offsetExists($name)) {
      return $this[$name];
    }
  }
}