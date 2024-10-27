<?php
require 'vendor/autoload.php';

use Google\Client;
use Google\Service\Sheets;

function saveDataToSheets($data) {
    $client = new Client();
    $client->setApplicationName('Bus Parts Data');
    $client->setScopes(Sheets::SPREADSHEETS);
    $client->setAuthConfig('path/to/your/credentials.json'); // Update the path
    $service = new Sheets($client);

    $spreadsheetId = 'YOUR_SPREADSHEET_ID'; // Replace with your Google Sheet ID
    $range = 'Sheet1!A1'; // Adjust as needed

    $values = [
        ["ID", "Part Number", "Description", "Quantity", "Min Reorder Qty", "Last Reordered Date", "Supplier Info", "Price"], // Header
        ...$data // The data you want to insert
    ];
    
    $body = new Sheets\ValueRange(['values' => $values]);
    $params = ['valueInputOption' => 'RAW'];

    $result = $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
    return $result->getUpdatedCells();
}

// Fetch data from your database and save to Google Sheets
include_once 'db.php';
$result = $conn->query("SELECT * FROM bus_parts");
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [$row['id'], $row['part_number'], $row['description'], $row['quantity'], $row['min_reorder_qty'], $row['last_reordered_date'], $row['supplier_info'], $row['price']];
}

$updatedCells = saveDataToSheets($data);
echo "$updatedCells cells updated in Google Sheets.";
?>
