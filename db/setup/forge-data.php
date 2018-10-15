<?php
/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: setup/forge-data.php
 * Author: Chris Partridge
 *
 * Creates fraudulent test data for demonstration 10/15
 */

// Initializes database and establishes connection
include "./inc/config.php";
echo "Clearing activity_log table";
$mysqli->query("TRUNCATE `activity_log`");
echo " ...done" . PHP_EOL;

$countRows = $mysqli->query("SELECT * FROM `attack_patterns`");
$range = $countRows->num_rows;

echo $range . " items available." . PHP_EOL;

$numToGen = intval(readline("How many? "));

for($i = 0; $i < $numToGen; $i++) {
    $res = rand(0, $range - 1);
    $db = $mysqli->query("SELECT * FROM `attack_patterns` LIMIT " . $res . ", 1");
    $db = mysqli_fetch_assoc($db);
    $mysqli->query("INSERT INTO `activity_log` (`Timestamp`, `PatternID`, `Data`)
                VALUES (0, '" . $db["ID"] . "', 'FORGED_DATA')");
    echo "Inserted fake " . $db["Phase"] . " -> " . $db["Name"] . PHP_EOL;
}


// Terminates all utilized connections and ends script
include "./inc/close.php";
?>
