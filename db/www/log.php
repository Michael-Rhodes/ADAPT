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

// Abysmally long query to save the inbound data, now with proper internal ID, to the 'witnessed' table
$mysqli->query("INSERT INTO `witnessed` (`ID`, `ExternalID`, `Name`, `TimeUTC`, `ComputerName`)
VALUES ('" . $patternInformation["ID"] . "', '" . $patternInformation["ExternalID"] . "',
'" . $patternInformation["Name"] . "', '" . $mysqli->real_escape_string($eventData["UtcTime"]) . "', '" . $mysqli->real_escape_string($data["host"]["name"]) . "')");
echo "OK";

// Terminates all utilized connections and ends script
include "./inc/close.php";
?>
