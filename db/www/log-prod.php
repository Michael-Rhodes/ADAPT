<?php
/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: www/log.php
 * Author: Chris Partridge
 *
 * Acquires log data from POSTed JSON, and saves that data to database.
 */

// Initializes database and establishes connection
include "./inc/config.php";

// Acquire data from POST request as raw text
$data = file_get_contents('php://input');
$data = json_decode($data, true); // decode JSON

// If no data or incorrect JSON was received, fail out
if(!$data) {
    echo "FAIL";
    die();
}

// Get the event data, and extract the external ID of the incoming record - just a quick double explode
$eventData = $data["event_data"];
$firstHolster = explode(",", $eventData["RuleName"]);
$secondHolster = explode("=", $firstHolster[0]);
$externalID = $secondHolster[1];

// Fetch TTP information based on the external ID record
$dbPatternInformation = $mysqli->query("SELECT * FROM `attack_patterns` WHERE `ExternalID` = '" . $mysqli->real_escape_string($externalID) . "'");
$patternInformation = mysqli_fetch_assoc($dbPatternInformation);
$dateToSave = substr($mysqli->real_escape_string($eventData["UtcTime"]), 0, strpos($mysqli->real_escape_string($eventData["UtcTime"]), "."));

// Abysmally long query to save the inbound data, now with proper internal ID, to the 'witnessed' table
$mysqli->query("INSERT INTO `patterns_seen` (`ID`, `ExternalID`, `Name`, `Epoch`, `ComputerName`)
VALUES ('" . $patternInformation["ID"] . "', '" . $patternInformation["ExternalID"] . "',
'" . $patternInformation["Name"] . "', UNIX_TIMESTAMP(CONVERT_TZ('" . $dateToSave . "', '+00:00', 'SYSTEM')), '" . $mysqli->real_escape_string($data["host"]["name"]) . "')");
echo "OK";

// Terminates all utilized connections and ends script
include "./inc/close.php";
?>
