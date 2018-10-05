<?php
/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: tools/cti-ttp-frequency.php
 * Author: Chris Partridge
 *
 * Quickly fetches frequency information for TTPs, allowing us to hone our area of focus.
 */

$attackIDs = [];
$ctiAttackFiles = glob("/tmp/adapt/cti/enterprise-attack/attack-pattern/*.json");
foreach($ctiAttackFiles as $ctiAttackFile) {
    $ctiAttackData = file_get_contents($ctiAttackFile);
    $ctiAttackJSON = json_decode($ctiAttackData, true);
    $ctiAttackData = $ctiAttackJSON["objects"][0];

    # For filtering out certain content
    /*$render = false;
    foreach($ctiAttackData["x_mitre_platforms"] as $platforms) {
        if(strcmp("Windows", $platforms) === 0) {
            $render = true;
        }
    }
    if(!$render) {
        continue;
    }*/

    if(array_key_exists("revoked", $ctiAttackData)) {
        if($ctiAttackData["revoked"]) {
            continue;
        }
    }

    $attackIDs[$ctiAttackData["id"]] = $ctiAttackData["name"];
}

$relations = [];
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

    if(!array_key_exists($ctiRelationshipData["target_ref"], $attackIDs)) {
        continue;
    }

    if(!array_key_exists($attackIDs[$ctiRelationshipData["target_ref"]], $relations)) {
        $relations[$attackIDs[$ctiRelationshipData["target_ref"]]] = 1;
    } else {
        $relations[$attackIDs[$ctiRelationshipData["target_ref"]]]++;
    }
}
arsort($relations);
foreach($relations as $name => $ct) {
    if($ct != 1) {
        echo $name . " appears " . $ct . " times" . PHP_EOL;
    } else {
        echo $name . " appears " . $ct . " time" . PHP_EOL;
    }
}
?>
