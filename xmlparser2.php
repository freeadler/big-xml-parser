<?php

ini_set('max_execution_time', 600); //this time could be increased because server power

$start = microtime(true);

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

fwrite($fileOutput, "<users>");

$buffer = '';
$usersCount = 0;
$userOffset = 0;
$endBuffer = '';
//I used stream_get_line because in some situations it shows better perfomance than fgets
while($buffer = stream_get_line($fileInput, 65535, "")) {
  $userOffset = 0;
  if($endBuffer) {
    $buffer = $endBuffer . $buffer;
    $endBuffer = '';
  }
  $bufferlen = strlen($buffer);
  while($userOffset < $bufferlen) {   
    $userBegin = strpos($buffer, "<user>", $userOffset);
    if($userBegin === false) {
      $endBuffer = substr($buffer, $userOffset);
      break;
    }

    $userEnd = strpos($buffer, "</user>", $userBegin);
    if($userEnd !== false) {
      $userEnd += 7;
      $currentUserXml = substr($buffer, $userOffset, $userEnd - $userOffset);

      $ageStart = strpos($currentUserXml, "<age>") + 5;
      $ageEnd = strpos($currentUserXml, "</age>", $ageStart);
      $age = intval(substr($currentUserXml, $ageStart, $ageEnd - $ageStart));

      if($userAgeMin <= $age && $age <= $userAgeMax) {
        fwrite($fileOutput, $currentUserXml);
        $usersCount++;
        echo "\rUsers found: $usersCount";
        flush();
      }

      $userOffset = $userEnd;
    } else {
      $endBuffer = substr($buffer, $userOffset);
      break;
    }
  }
}

$buffer = '';
fwrite($fileOutput, "\n</users>");

fclose($fileInput);
fclose($fileOutput);

$end = microtime(true);

echo "\ntime spent: ".($end - $start)."\n";
// for 2 millions users it takes 15 seconds