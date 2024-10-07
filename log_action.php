<?php
// log_action.php

/**
 * Function to log actions to a file.
 *
 * @param string $action The action to log (e.g., "Ticket generated").
 * @param string $details Additional details about the action.
 */
function log_action($action, $details) {
    $logfile = 'logs/actions.log';
    $timestamp = date("Y-m-d H:i:s");

    // Create a log entry
    $log_entry = "[$timestamp] $action: $details\n";

    // Append log entry to log file
    file_put_contents($logfile, $log_entry, FILE_APPEND);
}
?>
