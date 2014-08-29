<?php

if ($_SERVER["REQUEST_URI"] == "/config.php"){
  header("Location: http://$_SERVER[SERVER_NAME]");
  exit();

}
$stringfromfile = file('../../.git/packed-refs', FILE_USE_INCLUDE_PATH);
$arraySize = count($stringfromfile);
$location = $stringfromfile[$arraySize - 1];
$split = explode("/", $location);
$versionSize = count($split);
$version = $split[$versionSize - 1];
if($branchname){
  $version .= "- SNAPSHOT";
}
$stringfromfile = file('../../.git/HEAD', FILE_USE_INCLUDE_PATH);
$stringfromfile = $stringfromfile[0]; //get the string from the array

$explodedstring = explode("/", $stringfromfile); //seperate out by the "/" in the string
$branchname = $explodedstring[2]; //get the one that is always the branch name
echo "<div style='clear: both; width: 100%; font-size: 14px; font-family: Helvetica; color: #30121d; background: #bcbf77; padding: 20px; text-align: center;'>Current Branch:
  <span style='color:#fff; font-weight: bold; text-transform: uppercase;'>" . $branchname . "</span>
  </div>"; //show it on the page
echo "<div style='clear: both; width: 100%; font-size: 14px; font-family: Helvetica; color: #30121d; background: #bcbf77; padding: 20px; text-align: center;'>Current Version:
  <span style='color:#fff; font-weight: bold; text-transform: uppercase;'>" . $version . "</span>
  </div>"; //show it on the page



?>
