<?php

namespace Parsec;
use \Parsec\Argument\Input;
use \Parsec\Argument\Output;
use \Parsec\Argument\MaxAge;
use \Parsec\Argument\MinAge;
use \Parsec\Collection\ArgumentsCollection;
use \Parsec\Xml\UsersReader;
use \Parsec\Xml\UsersWriter;
use \Parsec\Exception\ArgumentException;
use \Parsec\Exception\ParseException;

class Parsec
{
  protected $_args;

  public function __construct()
  {
    try {
      $this->_init();
    } catch (ArgumentException $e) {
      echo "Error! ".$e->getMessage()."\n";
      die();
    }
  }

  protected function _init() 
  {
    $args = new ArgumentsCollection();
    $args->addArgument(new Input(true));
    $args->addArgument(new Output(true));
    $args->addArgument(new MaxAge());
    $args->addArgument(new MinAge());

    $this->_args = Argumentator::getArguments($args);
    $minAge = $this->_args->MinAge->getValue();
    $maxAge = $this->_args->MaxAge->getValue();
    if($minAge == null) {
      $this->_args->MinAge->setValue(20);
      $minAge = $this->_args->MinAge->getValue();
    }
    if($maxAge == null) {
      $this->_args->MaxAge->setValue(30);
      $maxAge = $this->_args->MinAge->getValue();
    }
    if($minAge > $maxAge) {
      throw new ArgumentException("Max age can't be less than Min age");
    }
  }

  public function run()
  {
    $ur = new UsersReader($this->_args->Input->getValue());
    $uw = new UsersWriter($this->_args->Output->getValue());
    $minAge = $this->_args->MinAge->getValue();
    $maxAge = $this->_args->MaxAge->getValue();

    $usersCount = 0;
    while($user = $ur->getNextUser()) {
      if($minAge <= $user->getAge() && $user->getAge() <= $maxAge) {
        $uw->writeUser($user);
      }
      $usersCount++;
      echo "\rUsers found: $usersCount";
    }
    $uw->close();
  }
}