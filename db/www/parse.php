<?php
/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: www/parse.php
 * Author: Chris Partridge
 *
 * Brief theory demonstration 10/15
 */

// Initializes database and establishes connection
include "./inc/config.php";

echo "<html><head></head><body>";
/*echo "<style>
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
echo "<div class='row'><div class='column'>";*/

$phaseInfo = [];
$dbGetPhaseInformation = $mysqli->query("SELECT * FROM `attack_phases`");
while($getPhaseInformation = mysqli_fetch_assoc($dbGetPhaseInformation)) {
    $phaseInfo[$getPhaseInformation["Name"]] = $getPhaseInformation["Order"];
}

// TEST DATA
/*
$activityIDs = [];
$activityHistory = [];
$dbGetActivityInformation = $mysqli->query("SELECT * FROM `activity_log`");
while($getActivityInformation = mysqli_fetch_assoc($dbGetActivityInformation)) {
    $activityHistory[] = $getActivityInformation;
    $activityIDs[] = $getActivityInformation["PatternID"];
}
*/

// LIVE DATA
$activityIDs = [];
$activityHistory = [];
$numEventsWitnessed = 0;
$dbGetActivityInformation = $mysqli->query("SELECT * FROM `witnessed`");
while($getActivityInformation = mysqli_fetch_assoc($dbGetActivityInformation)) {
    $activityHistory[] = $getActivityInformation;
    $activityIDs[] = $getActivityInformation["ID"];
    $numEventsWitnessed++;
}

$activityIDs = array_unique($activityIDs);

$patternInfo = [];
foreach($activityIDs as $actID) {
    $dbGetPatternInformation = $mysqli->query("SELECT * FROM `attack_patterns` WHERE `ID` = '" . $actID . "'");
    $getPatternInformation = mysqli_fetch_assoc($dbGetPatternInformation);
    $patternInfo[$actID] = $getPatternInformation;
}

//echo "<p>--- Flagged attack patterns (" . count($activityHistory) . ") ---</p>";
foreach($activityHistory as $historical) {
    $thisActivity = $patternInfo[$historical["ID"]];
    //echo "<p>" . $thisActivity["Name"] . " (" . $thisActivity["Phase"] . "), logged at " . $historical["TimeUTC"] . " on host " . $historical["ComputerName"] . "</p>";
}
//echo "</div><div class='column'>";

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

    // num*MatchingActivities
    $entityRels = [];
    while($countEntityRelationships = mysqli_fetch_assoc($dbCountEntityRelationships)) {
        if(strpos($countEntityRelationsips["TargetID"], "malware--") === false) {
            $entityRels[] = $countEntityRelationships["TargetID"];
        }
    }
    $numTTPsAvailable = count($entityRels);
    $matchingEntityRels = [];
    foreach($activityHistory as $historical) {
        if(in_array($historical["ID"], $entityRels)) {
            $matchingEntityRels[] = $historical["ID"];
        }
    }
    $numMatchingActivities = count($matchingEntityRels);
    $numUniqMatchingActivities = $entityFreq;

    // num*Coverage
    $numTotalCoverage = 11; // hardcoded for now
    $entityCoverage = [];
    $uniqMatchingEntityRels = array_unique($matchingEntityRels);
    foreach($uniqMatchingEntityRels as $histmatch) {
        $dbCountEntityCoverage = $mysqli->query("SELECT * FROM `attack_patterns` WHERE `ID` = '" . $histmatch . "'");

        while($countEntityCoverage = mysqli_fetch_assoc($dbCountEntityCoverage)) {
            $entityCoverage[$countEntityCoverage["Phase"]] = 1;
        }
    }
    $numTTPCoverage = count($entityCoverage);

    // Stats about stats computed
    $percentMatchingEvents = $numMatchingActivities/$numEventsWitnessed;
    $percentMatchingTTPs = $numUniqMatchingActivities/$numTTPsAvailable;
    $percentMatchingCoverage = $numTTPCoverage/$numTotalCoverage;

    // Data available for analysis:
    // $numMatchingActivities = number of nonunique activities matching
    // $numEventsWitnessed = number of entries in `witnessed`
    // $numUniqMatchingActivities = number of unique activities matching
    // numTTPsAvailable = number of TTPs that the APT can access, excluding malware
    echo "<p>" . $type . " '" . $getEntityInformation["Name"] . "' has following stats: ";
    echo "matching events " . $numMatchingActivities . ", ";
    echo "total events " . $numEventsWitnessed . ", ";
    echo "<b>%events " . round((100 * $percentMatchingEvents), 2) . "</b>, ";
    echo "matching TTPs " . $numUniqMatchingActivities . ", ";
    echo "available TTPs " . $numTTPsAvailable . ", ";
    echo "<b>%TTPs " . round((100 * $percentMatchingTTPs), 2) . "</b>, ";
    echo "coverage " . $numTTPCoverage . "/" . $numTotalCoverage . ", ";
    echo "<b>%coverage " . round((100 * $percentMatchingCoverage), 2) . "</b>, ";
    echo "<b><u>FINAL VALUE: " . round((100 * ($percentMatchingEvents * $percentMatchingTTPs * $percentMatchingCoverage)), 2) . "</b></u>";
    /*
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
    */
}

echo "</div></div></body></html>";

// Terminates all utilized connections and ends script
include "./inc/close.php";
?>
