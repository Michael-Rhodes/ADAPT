<?php
/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: setup/generate-loader-endpt.php
 * Author: Chris Partridge
 *
 * Creates simple API endpoints for Michael to run atomic simulations.
 * Must be kept updated with `witnessed` table spec per "setup/create-adapt-db.sql"
 * and ELK->ADAPT endpoint code per "www/log.php"
 * 
 * Table copy is created in the format `adapt`.`ldwit-<name>`
 * Loader endpt is default written to "/var/www/html/ldept-<name>.php"
 */

// Initializes database and establishes connection
include "./inc/config.php";
if($argc != 2) {
    echo "not enough args" . PHP_EOL;
    include "./inc/close.php";
}

$newName = $argv[1];

echo "Creating new table for data consumption" . PHP_EOL;
$mysqli->query("CREATE TABLE `ldwit-" . $newName . "` (
  `ID` varchar(64) NOT NULL,
  `ExternalID` varchar(64) NOT NULL,
  `Name` varchar(64) NOT NULL,
  `TimeUTC` varchar(32) DEFAULT NULL,
  `ComputerName` varchar(256) DEFAULT NULL,
  KEY `FastAccessByID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

echo "Creating new endpoint for data consumption" . PHP_EOL;
$endptContents = '<?php
/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: www/ldept-' . $newName . '.php
 * Author: a bot lmao
 *
 * For atomic testing only. Should be removed whenever testing is completed.
 */

include "./inc/config.php";

$data = file_get_contents("php://input");
$data = json_decode($data, true);

if(!$data) {
    echo "FAIL";
    die();
}

$eventData = $data["event_data"];
$firstHolster = explode(",", $eventData["RuleName"]);
$secondHolster = explode("=", $firstHolster[0]);
$externalID = $secondHolster[1];

$dbPatternInformation = $mysqli->query("SELECT * FROM `attack_patterns` WHERE `ExternalID` = \'" . $mysqli->real_escape_string($externalID) . "\'");
$patternInformation = mysqli_fetch_assoc($dbPatternInformation);

$mysqli->query("INSERT INTO `witnessed` (`ID`, `ExternalID`, `Name`, `TimeUTC`, `ComputerName`)
VALUES (\'" . $patternInformation["ID"] . "\', \'" . $patternInformation["ExternalID"] . "\',
\'" . $patternInformation["Name"] . "\', \'" . $mysqli->real_escape_string($eventData["UtcTime"]) . "\', \'" . $mysqli->real_escape_string($data["host"]["name"]) . "\')");
echo "OK";

include "./inc/close.php";
?>';
file_put_contents("/var/www/html/ldept-" . $newName . ".php", $endptContents);

echo "Endpoint should be live at https://adapt.mns.llc/ldept-" . $newName . ".php" . PHP_EOL;

// Terminates all utilized connections and ends script
include "./inc/close.php";
?>
