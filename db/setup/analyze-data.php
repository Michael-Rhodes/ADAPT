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
    $groupInfo[$getGroupInformation["ID"]] = $getGroupInformation["Name"];
}
echo "Group information in memory" . PHP_EOL;

$malwareInfo = [];
$dbGetMalwareInformation = $mysqli->query("SELECT * FROM `known_malware`");
while($getMalwareInformation = mysqli_fetch_assoc($dbGetMalwareInformation)) {
    $malwareInfo[$getMalwareInformation["ID"]] = $getMalwareInformation["Name"];
}
echo "Malware information in memory" . PHP_EOL;

echo PHP_EOL . "--- Parsing group relationships ---" . PHP_EOL;

foreach($groupInfo as $groupID => $groupName) {
    echo "Analyzing " . $groupName . "... " . PHP_EOL;

    $malwareToMap = [];
    $dbGetRelationshipInformation = $mysqli->query("SELECT * FROM `known_relationships` WHERE SourceID = '" . $groupID . "'");
    while($getRelationshipInformation = mysqli_fetch_assoc($dbGetRelationshipInformation)) {
        if(strpos($getRelationshipInformation["TargetID"], "malware") !== false) {
            $malwareToMap[] = $getRelationshipInformation["TargetID"];
        }
    }

    foreach($malwareToMap as $malwareID) {
        $dbGetPseudorelInformation = $mysqli->query("SELECT * FROM `known_relationships` WHERE SourceID = '" . $malwareID . "'");
        while($getPseudorelInformation = mysqli_fetch_assoc($dbGetPseudorelInformation)) {
            $dbValidatePseudorelInformation = $mysqli->query("SELECT * FROM `known_relationships`
                                                                WHERE SourceID = '" . $groupID . "'
                                                                AND TargetID = '" . $getPseudorelInformation["TargetID"] . "'");
            
            if($dbValidatePseudorelInformation->num_rows === 0) {
                echo "Pseudorel: malware " . $malwareInfo[$malwareID] . " references " . $getPseudorelInformation["TargetID"] . PHP_EOL;
                $mysqli->query("INSERT INTO `known_relationships` (`ID`, `SourceID`, `Type`, `TargetID`, `Description`)
                                VALUES ('PSEUDO_RELATIONSHIP', '" . $groupID . "',
                                'may', '" . $getPseudorelInformation["TargetID"] . "',
                                '" . $groupName . " has access to this TTP via malware it is known to use, "
                                . $malwareInfo[$malwareID] . ", despite not having been observed using this TTP during engagements.')");
            }
        }
    }
}


// Terminates all utilized connections and ends script
include "./inc/close.php";
?>
