<?php
/**
 * Redirect to unified LEQs-xAI Satisfaction Evaluation
 */
$target = '../leqs-workshop/pages/evaluation.php';
if (!empty($_SERVER['QUERY_STRING'])) {
    $target .= '?' . $_SERVER['QUERY_STRING'];
}
header("Location: " . $target, true, 302);
exit();
