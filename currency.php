<?php
$databasePath = '/Users/arpine/Documents/my_database.db'; // Update with your path

try {
    // Connect to the SQLite database
    $pdo = new PDO("sqlite:$databasePath");
    echo "Connected successfully to the SQLite database!<br>";

    // Create the currency table if it does not exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS currency (
        code TEXT PRIMARY KEY,
        name TEXT NOT NULL
    )");

    // Fetch data from API
    $jsonUrl = "https://v6.exchangerate-api.com/v6/807785a2e7c20523e6f54e4e/codes";
    $jsonData = file_get_contents($jsonUrl);
    $currencyData = json_decode($jsonData, true);

    if ($currencyData && isset($currencyData['supported_codes'])) {
        // Delete previous data to prevent duplication
        $pdo->exec("DELETE FROM currency");

        // Prepare the insert statement
        $stmt = $pdo->prepare("INSERT INTO currency (code, name) VALUES (:code, :name)");

        // Insert each currency code and name into the database
        foreach ($currencyData['supported_codes'] as $currency) {
            $stmt->execute([
                ':code' => $currency[0],
                ':name' => $currency[1]
            ]);
        }

        echo "Data inserted into the 'currency' table successfully.<br>";
    } else {
        echo "Failed to retrieve data from the API.";
    }

    // Display the data from the 'currency' table
    echo "<h3>Currency Codes and Names:</h3>";
    $stmt = $pdo->query("SELECT * FROM currency");
    echo "<table border='1'>
            <tr><th>Code</th><th>Name</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>{$row['code']}</td><td>{$row['name']}</td></tr>";
    }
    echo "</table>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

