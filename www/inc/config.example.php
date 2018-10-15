<?php
/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: www/inc/config.php
 * Author: Chris Partridge
 *
 * Configures and initializes certain values for script usage.
 */

// YOU PROBABLY HAVE TO EDIT THESE
$configDbAddr = "____________"; // Database IP address or hostname
$configDbUser = "____________"; // ADAPT *UNPRIVILEGED* user
$configDbPass = "____________"; // ADAPT *UNPRIVILEGED* password
// YOU PROBABLY HAVE TO EDIT THESE

// YOU PROBABLY DON'T HAVE TO EDIT THESE
$configDbDb = "adapt"; // database to use
// YOU PROBABLY DON'T HAVE TO EDIT THESE

// AND DON'T TOUCH BEYOND THIS LINE UNLESS YOU REALLY KNOW WHAT YOU'RE DOING
$mysqli = new mysqli($configDbAddr, $configDbUser, $configDbPass, $configDbDb);
if ($mysqli->connect_errno) {
    exit();
}
?>
