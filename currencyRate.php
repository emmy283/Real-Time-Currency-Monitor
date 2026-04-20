<?php
session_start(); // Start a session to store the history of commands

$databasePath = '/Users/arpine/Documents/my_database.db'; // Update with your actual path

try {
    // Create (or open) the SQLite database connection
    $pdo = new PDO("sqlite:$databasePath");
    
    // Create the currency_rate table if it doesn't exist, including a timestamp
    $pdo->exec("CREATE TABLE IF NOT EXISTS currency_rate (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        rates TEXT NOT NULL,
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
       
    )");

    // Fetch data from the API
    $apiUrl = "https://v6.exchangerate-api.com/v6/807785a2e7c20523e6f54e4e/latest/USD";
    $response = file_get_contents($apiUrl);
    
    if ($response === FALSE) {
        die("Error fetching data from API.");
    }

    $data = json_decode($response, true);
    
    if ($data['result'] !== 'success') {
        die("Error fetching valid data from the API.");
    }

    $currencyRates = json_encode($data['conversion_rates']); // Store the rates as a JSON string

    // Check if the rates have changed
    $stmt = $pdo->query("SELECT * FROM currency_rate ORDER BY id DESC LIMIT 1");
    $lastEntry = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($lastEntry) {
        $lastRates = $lastEntry['rates'];

        // If the rates are different from the last entry, insert the new data
        if ($lastRates !== $currencyRates) {
            $stmt = $pdo->prepare("INSERT INTO currency_rate (rates) VALUES (:rates)");
            $stmt->execute([':rates' => $currencyRates]);
            echo "Currency rates updated.<br>";
        } else {
            echo "No change in currency rates. Data not updated.<br>";
        }
    } else {
        // If no previous entry exists, insert the first entry
        $stmt = $pdo->prepare("INSERT INTO currency_rate (rates) VALUES (:rates)");
        $stmt->execute([':rates' => $currencyRates]);
        echo "Currency rates inserted.<br>";
    }

    // Retrieve all currency rate entries from the database
    $stmt = $pdo->query("SELECT * FROM currency_rate ORDER BY id DESC");
    $currencyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Rates</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
        table {
            width: 70%;
            margin-top: 20px;
            border-collapse: collapse;
            margin-left: auto;
            margin-right: auto;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px 12px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }

        .currency-table {
            margin-top: 20px;
            width: 70%;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            background-color: #fff;
        }
    </style>
</head>
<body>

<h1>Currency Rate Table</h1>

<!-- Display the currency rate data -->
<div class="currency-table">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Timestamp</th>
                <th>Currency</th>
                <th>Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($currencyData) {
                $counter = 1;  // Start the ID from 1
                
                foreach ($currencyData as $entry) {
                    // Decode the JSON string into an array
                    $rates = json_decode($entry['rates'], true);
                    $timestamp = $entry['timestamp'];
                    
                    // For each currency and its rate, display a row in the table
                    foreach ($rates as $currency => $rate) {
                        echo "<tr>
                                <td>" . $counter++ . "</td>  <!-- Increment the ID -->
                                <td>" . $timestamp . "</td>
                                <td>" . $currency . "</td>
                                <td>" . $rate . "</td>
                              </tr>";
                    }
                }
            } else {
                echo "<tr><td colspan='4'>No data available.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>


