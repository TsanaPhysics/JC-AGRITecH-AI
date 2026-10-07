<?php
/**
 * Redirect to unified LEQs-xAI Certificate View & Print
 */
$target = '../leqs-workshop/pages/certificate_view.php';
if (!empty($_SERVER['QUERY_STRING'])) {
    $target .= '?' . $_SERVER['QUERY_STRING'];
}
header("Location: " . $target, true, 302);
exit();
