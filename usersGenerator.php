<?php

ini_set('max_execution_time', 300); //this time could be increased because server power

$options = getopt("", ["output:","users::", "agemin::","agemax::"]);

$outputFilename = (isset($options['output']) ? $options['output'] : '');

$userAgeMin = isset($options['agemin']) ? intval($options['agemin']) : 10;
$userAgeMax = isset($options['agemax']) ? intval($options['agemax']) : 60;

$usersCount = isset($options['users']) ? intval($options['users']) : 1000000;

$percentStep = ceil($usersCount / 100);
$percent = 0;

$fileOutput = fopen($outputFilename, 'w');
fwrite($fileOutput, "<users>\n");

for($i=1, $pi=0; $i<$usersCount; $i++, $pi++) {
  $age = rand($userAgeMin, $userAgeMax);
  $userXml = "\t<user>\n\t\t<id>$i</id>\n\t\t<name>User Name $i</name>\n\t\t<email>user$i@email.com</email>\n\t\t<age>$age</age>\n\t</user>\n";
  fwrite($fileOutput, $userXml);

  if($pi == $percentStep) {
    $pi = 0;
    $percent++;
    printf("\r%'.".($percent+1)."u%%", $percent);
    flush();
  }
}

fwrite($fileOutput, "</users>");

fclose($fileOutput);
echo "\nDone\n";