<?php
// Database connection
function connect_db() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    return $conn;
}

// Sanitize input data
function sanitize($data) {
    $conn = connect_db();
    return mysqli_real_escape_string($conn, trim($data));
}

// Execute query and return result
function query($sql) {
    $conn = connect_db();
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    return $result;
}

// Get a single row from query
function fetch_assoc($result) {
    return mysqli_fetch_assoc($result);
}

// Get all rows from query
function fetch_all($result) {
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

// Get number of rows
function num_rows($result) {
    return mysqli_num_rows($result);
}

// Get last inserted ID
function last_id() {
    $conn = connect_db();
    return mysqli_insert_id($conn);
}

// Close database connection
function close_db() {
    $conn = connect_db();
    mysqli_close($conn);
}
?>
