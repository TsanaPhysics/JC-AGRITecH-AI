<?php
/**
 * Redirect to unified LEQs-xAI Pre-test Assessment
 */
$target = '../leqs-workshop/pages/assessment_pre.php';
if (!empty($_SERVER['QUERY_STRING'])) {
    $target .= '?' . $_SERVER['QUERY_STRING'];
}
header("Location: " . $target, true, 302);
exit();
