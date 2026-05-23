<?php
require_once(__DIR__ . '/session.php');
setJsonHeaders();

echo json_encode([
    'session_id' => session_id(),
    'session_data' => $_SESSION,
    'is_logged_in' => isUserLoggedIn(),
    'cookie' => $_COOKIE
]);
?>
