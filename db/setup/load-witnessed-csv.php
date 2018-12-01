<?php
/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: setup/gload-witnessed-csv.php
 * Author: Chris Partridge
 *
 * Emergency loader for `witnessed` backup CSVs.
 */

// Initializes database and establishes connection
include "./inc/config.php";
$handle = fopen("witnessed.csv", "r");
if($handle) {
    while (($csv = fgets($handle)) !== false) {
        $expCsv = explode(";", trim($csv));
        $dateToSave = $variable = substr(str_replace('"', "", $expCsv[3]), 0, strpos(str_replace('"', "", $expCsv[3]), "."));

        // Abysmally long query to save the inbound data, now with proper internal ID, to the 'witnessed' table
        $mysqli->query("INSERT INTO `witnessed` (`ID`, `ExternalID`, `Name`, `Epoch`, `ComputerName`)
        VALUES ('" . str_replace('"', "", $expCsv[0]) . "', '" . str_replace('"', "", $expCsv[1]) . "',
        '" . str_replace('"', "", $expCsv[2]) . "', UNIX_TIMESTAMP(CONVERT_TZ('" . $dateToSave . "', '+00:00', 'SYSTEM')), '" . str_replace('"', "", $expCsv[4]) . "')");
        echo ".";
    }

    fclose($handle);
}

// Terminates all utilized connections and ends script
include "./inc/close.php";
?>
