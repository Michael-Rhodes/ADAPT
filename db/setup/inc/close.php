<?php
/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: setup/inc/close.php
 * Author: Chris Partridge
 *
 * Tears down external connections cleanly before script exit.
 */

$mysqli->close();
exit();
?>
