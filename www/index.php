<?php
/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: www/index.php
 * Author: Chris Partridge
 *
 * Brief theory demonstration 10/15
 */

// Initializes database and establishes connection
include "./inc/config.php";

echo "<html><head></head><body>";
echo "<style>
* {
    box-sizing: border-box;
}

.row {
    display: flex;
}

.column {
    flex: 50%;
    padding: 10px;
</style>";
echo "<div class='row'><div class='column'>";

$phaseInfo = [];
$dbGetPhaseInformation = $mysqli->query("SELECT * FROM `attack_phases`");
while($getPhaseInformation = mysqli_fetch_assoc($dbGetPhaseInformation)) {
    $phaseInfo[$getPhaseInformation["Name"]] = $getPhaseInformation["Order"];
}

$activityIDs = [];
$activityHistory = [];
$dbGetActivityInformation = $mysqli->query("SELECT * FROM `activity_log`");
while($getActivityInformation = mysqli_fetch_assoc($dbGetActivityInformation)) {
    $activityHistory[] = $getActivityInformation;
    $activityIDs[] = $getActivityInformation["PatternID"];
}

$activityIDs = array_unique($activityIDs);

$patternInfo = [];
foreach($activityIDs as $actID) {
    $dbGetPatternInformation = $mysqli->query("SELECT * FROM `attack_patterns` WHERE `ID` = '" . $actID . "'");
    $getPatternInformation = mysqli_fetch_assoc($dbGetPatternInformation);
    $patternInfo[$actID] = $getPatternInformation;
}

echo "<p>--- Flagged attack patterns (" . count($activityHistory) . ") ---</p>";
foreach($activityHistory as $historical) {
    $thisActivity = $patternInfo[$historical["PatternID"]];
    echo "<p>" . $thisActivity["Name"] . " (" . $thisActivity["Phase"] . "), logged at " . $historical["Timestamp"] . " on host N/A</p>";
}

echo "</div><div class='column'>";

$entityStats = [];
foreach($activityIDs as $actID) {
    $dbGetRelationshipInformation = $mysqli->query("SELECT * FROM `known_relationships` WHERE `TargetID` = '" . $actID . "'");
    while($getRelationshipInformation = mysqli_fetch_assoc($dbGetRelationshipInformation)) {
        if(array_key_exists($getRelationshipInformation["SourceID"], $entityStats)) {
            $entityStats[$getRelationshipInformation["SourceID"]]++;
        } else {
            $entityStats[$getRelationshipInformation["SourceID"]] = 1;
        }
    }
}

arsort($entityStats);

echo "<p>--- Matching entities: ---</p>";

foreach($entityStats as $entityKey => $entityFreq) {
    if(strpos($entityKey, "malware--") !== false) {
        $dbGetEntityInformation = $mysqli->query("SELECT * FROM `known_malware` WHERE `ID` = '" . $entityKey . "'");
        $type = "Malware";
        continue;
    } else {
        $dbGetEntityInformation = $mysqli->query("SELECT * FROM `known_groups` WHERE `ID` = '" . $entityKey . "'");
        $type = "APT";
    }
    $getEntityInformation = mysqli_fetch_assoc($dbGetEntityInformation);
    $dbCountEntityRelationships = $mysqli->query("SELECT * FROM `known_relationships` WHERE `SourceID` = '" . $entityKey . "'");
    echo "<p>" . $type . " '" . $getEntityInformation["Name"] . "' has " . $dbCountEntityRelationships->num_rows . " known patterns, " . $entityFreq . " of which are seen here (";
    $percent = round($entityFreq/$dbCountEntityRelationships->num_rows, 3) * 100;
    if($percent > 50) {
        echo "<b>" . $percent . "% match</b>, ";
    } else {
        echo $percent . "% match, ";
    }
    $percent = 100 - (round($entityFreq/count($activityIDs), 3) * 100);
    if($percent < 50) {
        echo "<b>" . $percent . "% extraneous</b>)</p>";
    } else {
        echo $percent . "% extraneous)</p>";
    }
}

echo "</div></div></body></html>";

// Terminates all utilized connections and ends script
include "./inc/close.php";
?>
