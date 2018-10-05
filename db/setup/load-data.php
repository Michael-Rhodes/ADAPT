<?php
/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: setup/load-data.php
 * Author: Chris Partridge
 *
 * Downloads and parses MITRE ATT&CK data, as well as uploads said data to a specified MySQL-compatible server.
 * If other open threat information about APTs becomes available, it should also include those as well.
 */

// Initializes database and establishes connection
include "./inc/config.php";

echo PHP_EOL . "--- Cleaning/regenerating working files ---" . PHP_EOL;
exec("rm -rf /tmp/adapt/cti &2>/dev/null");
exec("git clone https://github.com/mitre/cti /tmp/adapt/cti");

echo PHP_EOL . "--- Reloading attack phases ---" . PHP_EOL;
$attackPhasesDefinition = array(
    [0, "initial-access"],
    [1, "execution"],
    [2, "persistence"],
    [3, "privilege-escalation"],
    [4, "defense-evasion"],
    [5, "credential-access"],
    [6, "discovery"],
    [7, "lateral-movement"],
    [8, "collection"],
    [9, "exfiltration"],
    [10, "command-and-control"]
);
$attackPhaseSanityCheck = [];
foreach($attackPhasesDefinition as $attackPhase) {
    echo "Setting phase " . $attackPhase[0] . " to " . $attackPhase[1] . PHP_EOL;
    $mysqli->query("INSERT INTO `attack_phases` (`Order`, `Name`)
                    VALUES ('" . $attackPhase[0] . "', '" . $attackPhase[1] . "')
                    ON DUPLICATE KEY UPDATE `Name` = '" . $attackPhase[1] . "'");
    $attackPhaseSanityCheck[$attackPhase[1]] = $attackPhase[0];
}

echo PHP_EOL . "--- Loading attack patterns ---" . PHP_EOL;
$attackIDs = [];
$mysqli->query("TRUNCATE `attack_patterns`");
$ctiAttackFiles = glob("/tmp/adapt/cti/enterprise-attack/attack-pattern/*.json");
foreach($ctiAttackFiles as $ctiAttackFile) {
    $ctiAttackData = file_get_contents($ctiAttackFile);
    $ctiAttackJSON = json_decode($ctiAttackData, true);
    $ctiAttackData = $ctiAttackJSON["objects"][0];

    echo $ctiAttackData["name"] . "... ";
    if(array_key_exists("revoked", $ctiAttackData)) {
        if($ctiAttackData["revoked"]) {
            echo "REVOKED" . PHP_EOL;
            continue;
        }
    }

    $attackIDs[$ctiAttackData["id"]] = $ctiAttackData["name"];

    for($i = 0; $i < count($ctiAttackData["external_references"]); $i++) {
        if(strcmp($ctiAttackData["external_references"][$i]["source_name"], "mitre-attack") === 0) {
            $ctiAttackURL = $ctiAttackData["external_references"][$i]["url"];
            $ctiAttackID = $ctiAttackData["external_references"][$i]["external_id"];
        }
    }

    for($i = 0; $i < count($ctiAttackData["kill_chain_phases"]); $i++) {
        $ctiAttackPhase = $ctiAttackData["kill_chain_phases"][$i]["phase_name"];
        $mysqli->query("INSERT INTO `attack_patterns` (`ID`, `Name`, `Phase`, `URL`, `ExternalID`)
                        VALUES ('" . $ctiAttackData["id"] . "', '" . $ctiAttackData["name"] . "',
                        '" . $ctiAttackPhase . "', '" . $ctiAttackURL . "', '" . $ctiAttackID . "')");
        echo "phase " . $attackPhaseSanityCheck[$ctiAttackPhase] . " ";
    }
    echo "...done" . PHP_EOL;
}

echo PHP_EOL . "--- Loading known groups ---" . PHP_EOL;
$groupIDs = [];
$mysqli->query("TRUNCATE `known_groups`");
$ctiIntruderFiles = glob("/tmp/adapt/cti/enterprise-attack/intrusion-set/*.json");
foreach($ctiIntruderFiles as $ctiIntruderFile) {
    $ctiIntruderData = file_get_contents($ctiIntruderFile);
    $ctiIntruderJSON = json_decode($ctiIntruderData, true);
    $ctiIntruderData = $ctiIntruderJSON["objects"][0];

    echo $ctiIntruderData["name"] . "... ";
    if(array_key_exists("revoked", $ctiIntruderData)) {
        if($ctiIntruderData["revoked"]) {
            echo "REVOKED, SKIPPING" . PHP_EOL;
            continue;
        }
    }

    $groupIDs[$ctiIntruderData["id"]] = $ctiIntruderData["name"];

    for($i = 0; $i < count($ctiIntruderData["external_references"]); $i++) {
        if(strcmp($ctiIntruderData["external_references"][$i]["source_name"], "mitre-attack") === 0) {
            $ctiIntruderURL = $ctiIntruderData["external_references"][$i]["url"];
            $ctiIntruderID = $ctiIntruderData["external_references"][$i]["external_id"];
        }
    }

    $ctiIntruderAliases = "[";
    for($i = 0; $i < count($ctiIntruderData["aliases"]); $i++) {
        $ctiIntruderAliases .= $ctiIntruderData["aliases"][$i] . ", ";
    }
    $ctiIntruderAliases = rtrim($ctiIntruderAliases, ", ") . "]";

    $mysqli->query("INSERT INTO `known_groups` (`ID`, `Name`, `Aliases`, `URL`, `ExternalID`)
                    VALUES ('" . $ctiIntruderData["id"] . "', '" . $ctiIntruderData["name"] . "',
                    '" . $ctiIntruderAliases . "', '" . $ctiIntruderURL . "', '" . $ctiIntruderID . "')");
    echo "...done" . PHP_EOL;
}

echo PHP_EOL . "--- Loading relevant relationships ---" . PHP_EOL;
$mysqli->query("TRUNCATE `known_relationships`");
$ctiRelationshipFiles = glob("/tmp/adapt/cti/enterprise-attack/relationship/*.json");
foreach($ctiRelationshipFiles as $ctiRelationshipFile) {
    $ctiRelationshipData = file_get_contents($ctiRelationshipFile);
    $ctiRelationshipJSON = json_decode($ctiRelationshipData, true);
    $ctiRelationshipData = $ctiRelationshipJSON["objects"][0];

    if(array_key_exists("revoked", $ctiRelationshipData)) {
        if($ctiRelationshipData["revoked"]) {
            continue;
        }
    }

    if(!array_key_exists($ctiRelationshipData["source_ref"], $groupIDs) || !array_key_exists($ctiRelationshipData["target_ref"], $attackIDs)) {
        continue;
    }

    echo $groupIDs[$ctiRelationshipData["source_ref"]];
    echo " " . $ctiRelationshipData["relationship_type"] . " ";
    echo $attackIDs[$ctiRelationshipData["target_ref"]];

    $mysqli->query("INSERT INTO `known_relationships` (`ID`, `SourceID`, `Type`, `TargetID`)
                    VALUES ('" . $ctiRelationshipData["id"] . "', '" . $ctiRelationshipData["source_ref"] . "',
                    '" . $ctiRelationshipData["relationship_type"] . "', '" . $ctiRelationshipData["target_ref"] . "')");
    echo " ...done" . PHP_EOL;
}

// Terminates all utilized connections and ends script
include "./inc/close.php";
?>
