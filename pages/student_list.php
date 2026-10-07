<?php
/**
 * Redirect to unified LEQs-xAI Student & Participant Directory
 */
$target = '../leqs-workshop/pages/student_list.php';
if (!empty($_SERVER['QUERY_STRING'])) {
    $target .= '?' . $_SERVER['QUERY_STRING'];
}
header("Location: " . $target, true, 302);
exit();
