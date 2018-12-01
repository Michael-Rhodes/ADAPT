<?php
/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: setup/overlap-gen.php
 * Author: Chris Partridge
 *
 * (Re)Loads overlap data into attack_pattern DB clone.
 */

// Initializes database and establishes connection
include "./inc/config.php";

$patternInfo = [];
$dbGetPatternInformation = $mysqli->query("SELECT * FROM `attack_patterns`");
while($getPatternInformation = mysqli_fetch_assoc($dbGetPatternInformation)) {
    $patternInfo[$getPatternInformation["ExternalID"]] = $getPatternInformation;
}

$rawOverlap = file_get_contents("overlap.json");
$jsonOverlap = json_decode($rawOverlap, true);
$overlap = $jsonOverlap["techniques"];

$mysqli->query("TRUNCATE `pseudo_patterns`");

foreach($overlap as $technique) {
    if(array_key_exists("score", $technique)) {
        if($technique["score"] === 100) {
            $dbGetPatternInformation = $mysqli->query("SELECT * FROM `attack_patterns` WHERE `ExternalID` = '" . $technique["techniqueID"] . "'");

            while($getPatternInformation = mysqli_fetch_assoc($dbGetPatternInformation)) {
                var_dump($getPatternInformation);
                $mysqli->query("INSERT INTO `pseudo_patterns` (`ID`, `Name`, `Phase`, `URL`, `ExternalID`)
                VALUES ('" . $getPatternInformation["ID"] . "', '" . $getPatternInformation["Name"] . "',
                '" . $getPatternInformation["Phase"] . "', '" . $getPatternInformation["URL"] . "',
                '" . $getPatternInformation["ExternalID"] . "')");
            }
        }
    }
}

// Terminates all utilized connections and ends script
include "./inc/close.php";
