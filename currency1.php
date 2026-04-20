<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Only respond with JSON if the `fetch` parameter is set
if (isset($_GET['fetch'])) {
    // Set the response content type to JSON
    header("Content-Type: application/json");

    // Database path (adjust as needed)
    $dbPath = '/Users/arpine/Documents/my_database.db';

    try {
        // Connect to the SQLite database
        $db = new PDO("sqlite:" . $dbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch the data from the `currency` table
        $stmt = $db->query("SELECT code, name FROM currency");
        $currencies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the data as JSON
        echo json_encode(["success" => true, "currencies" => $currencies]);

    } catch (PDOException $e) {
        // Return error message as JSON if database connection fails
        echo json_encode(["success" => false, "error" => "Database error: " . $e->getMessage()]);
    }

    exit;  // Stop further PHP processing
}
