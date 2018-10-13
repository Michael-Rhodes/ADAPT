<?php
/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: setup/analyze-data.php
 * Author: Chris Partridge
 *
 * Parses attack patterns, phases, groups, and existing relationships into transitional information.
 * This data will later be used for threat intelligence generation.
 */

// Initializes database and establishes connection
include "./inc/config.php";

echo PHP_EOL . "--- Loading attack phases and groups to parse ---" . PHP_EOL;

$phaseInfo = [];
$dbGetPhaseInformation = $mysqli->query("SELECT * FROM `attack_phases`");
while($getPhaseInformation = mysqli_fetch_assoc($dbGetPhaseInformation)) {
    $phaseInfo[$getPhaseInformation["Name"]] = $getPhaseInformation["Order"];
}

echo "Attack phases in memory" . PHP_EOL;

$groupInfo = [];
$dbGetGroupInformation = $mysqli->query("SELECT * FROM `known_groups`");
while($getGroupInformation = mysqli_fetch_assoc($dbGetGroupInformation)) {
    $groupInfo[$getGroupInformation["Name"]] = $getGroupInformation["ID"];
}
echo "Group information in memory" . PHP_EOL;

echo PHP_EOL . "--- Parsing group relationships ---" . PHP_EOL;

foreach($groupInfo as $groupName => $groupID) {
    //echo "Analyzing " . $groupName . "... " . PHP_EOL;

    $patternsToMap = [];
    $dbGetRelationshipInformation = $mysqli->query("SELECT * FROM `known_relationships` WHERE SourceID = '" . $groupID . "'");
    while($getRelationshipInformation = mysqli_fetch_assoc($dbGetRelationshipInformation)) {
        $patternsToMap[] = $getRelationshipInformation["TargetID"];
    }

    foreach($patternsToMap as $patternID) {
        echo "";
    }
}


// Terminates all utilized connections and ends script
include "./inc/close.php";
?>
