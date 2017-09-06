<?php

namespace Parsec;

class Argumentator
{
  public static function getArguments($argumentsCollection)
  {
    $optionsNames = [];
    foreach($argumentsCollection as $arg) {
      $optionsNames[] = $arg->getArgumentName();
    }

    $options = getopt("", $optionsNames);

    foreach($argumentsCollection as $i=>$arg) {
      if(isset($options[$arg->getName()])) {
        $arg->setValue($options[$arg->getName()]);
        $argumentsCollection[$i] = $arg;
      }
    }

    return $argumentsCollection;
  }
}