<?php
/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: www/api-aggregate.php
 * Author: Chris Partridge
 *
 * Outputs entire contents of database.
 */

// Initializes database and establishes connection
include "./inc/config.php";

// Enable CORS
header("Access-Control-Allow-Origin: *");

$phaseInfo = [];
$dbGetPhaseInformation = $mysqli->query("SELECT * FROM `attack_phases`");
while($getPhaseInformation = mysqli_fetch_assoc($dbGetPhaseInformation)) {
    $phaseInfo[$getPhaseInformation["Name"]] = $getPhaseInformation["Order"];
}

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
    $dbGetPatternInformation = $mysqli->query("SELECT * FROM `pseudo_patterns` WHERE `ID` = '" . $actID . "'");
    $getPatternInformation = mysqli_fetch_assoc($dbGetPatternInformation);
    $patternInfo[$actID] = $getPatternInformation;
}

foreach($activityHistory as $historical) {
    $thisActivity = $patternInfo[$historical["ID"]];
}

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
$arrOut = [];

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
        if(strpos($countEntityRelationships["TargetID"], "malware--") === false) {
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
    // $numTotalCoverage = 11; // hardcoded for now
    $entityCoverage = [];
    $uniqMatchingEntityRels = array_unique($matchingEntityRels);
    foreach($uniqMatchingEntityRels as $histmatch) {
        $dbCountEntityCoverage = $mysqli->query("SELECT * FROM `pseudo_patterns` WHERE `ID` = '" . $histmatch . "'");

        while($countEntityCoverage = mysqli_fetch_assoc($dbCountEntityCoverage)) {
            $entityCoverage[$countEntityCoverage["Phase"]] = 1;
        }
    }
    $numTTPCoverage = count($entityCoverage);

    $fullCoverage = [];
    $dbCountFullCoverage = $mysqli->query("SELECT * FROM `known_relationships` WHERE `SourceID` = '" . $entityKey . "'");
    while($countFullCoverage = mysqli_fetch_assoc($dbCountFullCoverage)) {
        if(strpos($countFullCoverage["TargetID"], "malware--") === false) {
            $dbConvertFullCoverage = $mysqli->query("SELECT * FROM `pseudo_patterns` WHERE `ID` = '" . $countFullCoverage["TargetID"] . "'");

            while($convertFullCoverage = mysqli_fetch_assoc($dbConvertFullCoverage)) {
                $fullCoverage[$convertFullCoverage["Phase"]] = 1;
            }
        }
    }
    $numTotalCoverage = count($fullCoverage);

    // Stats about stats computed
    $percentMatchingEvents = $numMatchingActivities/$numEventsWitnessed;
    $percentMatchingTTPs = $numUniqMatchingActivities/$numTTPsAvailable;
    $percentMatchingCoverage = $numTTPCoverage/$numTotalCoverage;

    // Data available for analysis:
    // $numMatchingActivities = number of nonunique activities matching
    // $numEventsWitnessed =  number of entries in `witnessed`
    // $numUniqMatchingActivities = number of unique activities matching
    // numTTPsAvailable = number of TTPs that the APT can access, excluding malware
    $bigArr = array(
        "group" => $getEntityInformation["Name"],
        "matching_events" => "" . $numMatchingActivities . "",
        "total_events" => "" . $numEventsWitnessed . "",
        "percent_of_events" => "" . round((100 * $percentMatchingEvents), 2) . "",
        "matching_ttps" => "" . $numUniqMatchingActivities . "",
        "available_ttps" => "" . $numTTPsAvailable . "",
        "percent_of_ttps" => "" . round((100 * $percentMatchingTTPs), 2) . "",
        "coverage" => $numTTPCoverage . "/" . $numTotalCoverage . "",
        "percent_of_coverage" => "" . round((100 * $percentMatchingCoverage), 2) . "",
        "final_value" => "" . round((100 * ($percentMatchingEvents * $percentMatchingTTPs * $percentMatchingCoverage)), 2) . ""
    );
    $arrOut[] = $bigArr;
}

echo json_encode($arrOut);

// Terminates all utilized connections and ends script
include "./inc/close.php";
?>
