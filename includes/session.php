<?php
// Start session if not already started
function start_session() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// Check if user is logged in
function is_logged_in() {
    start_session();
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function is_admin() {
    start_session();
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Check if user is student
function is_student() {
    start_session();
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'student';
}

// Redirect if not logged in
function require_login() {
    if (!is_logged_in()) {
        header("Location: " . SITE_URL . "/auth/login.php");
        exit();
    }
}

// Redirect if not admin
function require_admin() {
    require_login();
    if (!is_admin()) {
        header("Location: " . SITE_URL . "/index.php");
        exit();
    }
}

// Redirect if not student
function require_student() {
    require_login();
    if (!is_student()) {
        header("Location: " . SITE_URL . "/index.php");
        exit();
    }
}

// Set user session
function set_user_session($user_id, $username, $role) {
    start_session();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    $_SESSION['user_role'] = $role;
}

// Clear user session
function clear_session() {
    start_session();
    session_unset();
    session_destroy();
}
?>
