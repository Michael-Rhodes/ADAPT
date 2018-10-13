<?php
/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: setup/inc/config.php
 * Author: Chris Partridge
 *
 * Configures and initializes certain values for script usage.
 */

// YOU PROBABLY HAVE TO EDIT THESE
$configDbAddr = "____________"; // Database IP address or hostname
$configDbUser = "____________"; // ADAPT *PRIVILEGED* user
$configDbPass = "____________"; // ADAPT *PRIVILEGED* password
// YOU PROBABLY HAVE TO EDIT THESE

// YOU PROBABLY DON'T HAVE TO EDIT THESE
$configDbDb = "adapt"; // database to use
// YOU PROBABLY DON'T HAVE TO EDIT THESE

// AND DON'T TOUCH BEYOND THIS LINE UNLESS YOU REALLY KNOW WHAT YOU'RE DOING
$mysqli = new mysqli($configDbAddr, $configDbUser, $configDbPass, $configDbDb);
if ($mysqli->connect_errno) {
    exit();
} else {
    echo "Database connection established as " . $configDbUser . " to " . $configDbDb . PHP_EOL;
}
?>
