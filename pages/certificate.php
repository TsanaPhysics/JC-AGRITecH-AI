<?php
/**
 * Redirect to unified LEQs-xAI Certificate Eligibility Check
 */
$target = '../leqs-workshop/pages/certificate.php';
if (!empty($_SERVER['QUERY_STRING'])) {
    $target .= '?' . $_SERVER['QUERY_STRING'];
}
header("Location: " . $target, true, 302);
exit();
