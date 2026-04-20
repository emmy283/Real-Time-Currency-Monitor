# Real-Time-Currency-Monitor

A robust Full-Stack PHP application that monitors, stores, and displays real-time exchange rates. This project demonstrates advanced data handling techniques, including external API integration and database optimization to ensure data integrity.

## 🚀 Key Features
* **Live API Integration**: Consumes real-time conversion data from the ExchangeRate-API.
* **Smart Data Persistence**: Implements a change-detection algorithm that compares the latest API fetch with the last database entry, storing data only when rates fluctuate to minimize storage bloat.
* **Asynchronous UI**: Utilizes JavaScript Fetch API and AJAX to retrieve and display currency lists without requiring a page refresh.
* **Relational Data Storage**: Managed via a structured SQLite database for high-performance local data retrieval.

## 🛠 Technical Implementation
* **Backend (PHP)**: Processes API requests using `file_get_contents` and `json_decode`, and manages database transactions via **PDO (PHP Data Objects)**.
* **Frontend (HTML5/JavaScript)**: Built with a responsive design and an event-driven "Load Data" system to fetch live JSON endpoints.
* **Database (SQLite)**: Designed with optimized tables for `currency_rate` (log-based) and `currency` (static definitions).

## 📂 Project Structure
* `currencyRate.php`: The primary tracking engine with change-detection logic.
* `currency1.html`: The interactive user dashboard.
* `currency1.php`: Server-side JSON endpoint for AJAX requests.
* `currency.php`: Database seeder for initial currency definitions.

## 💻 Setup & Installation
1. Ensure you have a local PHP environment (like XAMPP or MAMP).
2. Point the `$databasePath` in the PHP files to your local `.db` file directory.
3. Run `currency.php` first to initialize and populate the currency tables.
4. Open `currency1.html` in your browser to view the live dashboard.
