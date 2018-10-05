<?php
/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: tools/cti-ttp-critical-info.php
 * Author: Chris Partridge
 *
 * A script of utility to generate relevant details in a human-ingestible manner about ATT&CK data provided by MITRE.
 */

$attackPhases = array(
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

foreach($attackPhases as $attackPhase) {
    echo " ------- " . strtoupper($attackPhase[1]) . " ------- " . PHP_EOL . PHP_EOL;
    $ctiAttackFiles = glob("/tmp/adapt/cti/enterprise-attack/attack-pattern/*.json");
    foreach($ctiAttackFiles as $ctiAttackFile) {
        $ctiAttackData = file_get_contents($ctiAttackFile);
        $ctiAttackJSON = json_decode($ctiAttackData, true);
        $ctiAttackData = $ctiAttackJSON["objects"][0];

        $render = false;
        foreach($ctiAttackData["kill_chain_phases"] as $phases) {
            if(strcmp($attackPhase[1], $phases["phase_name"]) === 0) {
                $render = true;
            }
        }
        if(!$render) {
            continue;
        }

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

        echo $ctiAttackData["name"] . " (";
        $string = "";
        foreach($ctiAttackData["kill_chain_phases"] as $phases) {
            $string .= $phases["phase_name"] . ", ";
        }
        echo rtrim($string, ", ");
        echo "), works on: ";
        foreach($ctiAttackData["x_mitre_platforms"] as $platforms) {
            echo $platforms . " ";
        }
        echo PHP_EOL;
        if(array_key_exists("x_mitre_data_sources", $ctiAttackData)) {
            echo "Data sources:" . PHP_EOL;
            foreach($ctiAttackData["x_mitre_data_sources"] as $sources) {
                echo $sources . PHP_EOL;
            }
        }
        if(array_key_exists("x_mitre_system_requirements", $ctiAttackData)) {
            echo "System requirements:" . PHP_EOL;
                foreach($ctiAttackData["x_mitre_system_requirements"] as $sysReqs) {
                echo $sysReqs;
            }
            echo PHP_EOL;
        }
        echo PHP_EOL;
    }
}
?>
