<?php

ini_set('max_execution_time', 300); //this time could be increased because server power

$options = getopt("i:o:", ["input:","output:","agemin::","agemax::"]);

$inputFilename = isset($options['i']) ? $options['i'] : (isset($options['input']) ? $options['input'] : '');
$outputFilename = isset($options['o']) ? $options['o'] : (isset($options['output']) ? $options['output'] : '');

if(isset($options['agemin']) && intval($options['agemin']) <= 0) {
  echo "Error! Min age can't be less or equal to zero";
  die();
}
if(isset($options['agemax']) && intval($options['agemax']) <= 0) {
  echo "Error! Max age can't be less or equal to zero";
  die();
}

$userAgeMin = isset($options['agemin']) ? intval($options['agemin']) : 20;
$userAgeMax = isset($options['agemax']) ? intval($options['agemax']) : 30;

if($userAgeMax < $userAgeMin) {
  echo "Error! Max age can't be less than Min age";
  die();
}

if(!$inputFilename || !$outputFilename) {
  echo "Error! There is no input and output XML filename in the arguments\n";
  die();
}

if(!file_exists($inputFilename)) {
  echo "Error! Input file does not exist\n";
  die();
}

$fileInput = fopen($inputFilename, 'r');
$fileOutput = fopen($outputFilename, 'w');

fwrite($fileOutput, "<users>\n");

$buffer = '';
$currentUserXml = '';
$currentUserAge = 0;
$tagStack = [];
$tagName = '';
$tagValue = '';
$tagIsOpen = false;
$tagIsClose = false;
$tagIsUser = false;
$linesCount = 0;
//I used stream_get_line because in some situations it shows better perfomance than fgets
while($buffer = stream_get_line($fileInput, 65535, "\n")) { 
  $buffer .= "\n";
  $linesCount++;
  $bufferLen = strlen($buffer);
  for($i=0; $i<$bufferLen; $i++) {
    if($buffer[$i] == '<') {
      $tagIsOpen = true;
      continue;
    }
    if($buffer[$i] == '/') {
      $tagIsClose = true;
      $tagIsOpen = false;
      continue;
    }
    if($buffer[$i] == '>') {
      if($tagIsOpen) {
        array_push($tagStack, substr($tagName, 0));
        if($tagName == "user") {
          $tagIsUser = true;
        }
      }
      if($tagIsClose) {
        $lastTag = array_pop($tagStack);
        if($lastTag !== $tagName) { //here we can just skip checking for bad XML files
          echo "Syntax error! Tag <".$lastTag."> closed by tag </".$tagName."> on line ".$linesCount."\n";
          die();
        }
        if($tagName == 'user') {
          if($userAgeMin <= $currentUserAge && $currentUserAge <= $userAgeMax) {
            fwrite($fileOutput, "\t<".$tagName.">\n".$currentUserXml."\t</".$tagName.">\n");
          }
          $currentUserXml = '';
          $currentUserAge = 0;
          $tagIsUser = false;
        }
        if($tagIsUser) {
          $currentUserXml .= "\t\t<".$tagName.">".$tagValue."</".$tagName.">\n";
        }
        if($tagName == 'age' && $tagIsUser) {
          $currentUserAge = intval($tagValue);
        }
      }
      $tagIsOpen = false;
      $tagIsClose = false;
      $tagName = '';
      $tagValue = '';
      continue;
    }
    if($tagIsOpen || $tagIsClose) {
      $tagName .= $buffer[$i];
    } else {
      $tagValue .= $buffer[$i];
    }
  }
}

$buffer = '';
fwrite($fileOutput, "</users>");

fclose($fileInput);
fclose($fileOutput);
