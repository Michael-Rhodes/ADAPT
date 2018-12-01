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
$dbGetTimeInformation = $mysqli->query("SELECT MIN(`Epoch`), MAX(`Epoch`) FROM `witnessed`");
$getTimeInformation = mysqli_fetch_assoc($dbGetTimeInformation);
$output = array(
    "min_epoch" => $getTimeInformation["MIN(`Epoch`)"],
    "max_epoch" => $getTimeInformation["MAX(`Epoch`)"]
);
echo json_encode($output);

// Terminates all utilized connections and ends script
include "./inc/close.php";
?>
