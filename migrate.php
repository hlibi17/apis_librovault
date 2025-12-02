<?php
// migrate.php - CLI script to migrate database from librovault.sql file

$host = "localhost";
$user = "root";
$pass = "";
$db   = "librovault";
$sqlFile = "librovault.sql";

// Connect to the database
$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error . PHP_EOL;
    exit(1);
}

// Read SQL file
$sql = file_get_contents($sqlFile);
if ($sql === false) {
    echo "Failed to read SQL file: $sqlFile" . PHP_EOL;
    exit(1);
}

// Execute multi query
if ($mysqli->multi_query($sql)) {
    do {
        // store first result set
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());

    echo "Database migration completed successfully." . PHP_EOL;
} else {
    echo "Error executing SQL: " . $mysqli->error . PHP_EOL;
    exit(1);
}

$mysqli->close();
?>
