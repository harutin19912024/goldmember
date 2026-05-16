<?php
$responseData = [];
$apiUrl = "https://api.metalpriceapi.com/v1/latest?api_key=328dbc54ea99eeba3e5ca4e64604cc19&base=XAU&currencies=USD";

$apiUrlYesterday = "https://api.metalpriceapi.com/v1/yesterday?api_key=328dbc54ea99eeba3e5ca4e64604cc19&base=XAU&currencies=USD";

// Fetch the data
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);
$logFile = __DIR__ . '/error.log';

function logError($message) {
    global $logFile;
    file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL, FILE_APPEND);
}

logError('RUNNNEDDDD');

// Check if the API call was successful
if (isset($data['rates']['USD'])) {
    $pricePerTroyOunce = $data['rates']['USD'];
    
    // Convert price per troy ounce to price per gram
    $pricePerGram = $pricePerTroyOunce / 31.1035;
     $pricePerGram = round($pricePerGram, 2);

    // Database connection details
    $host = 'localhost';        // Database host
    $db = 'gssamru_goldmember';      // Database name
    $user = 'gssamru_autoimport';    // Database username
    $pass = 'bGtVSaf2rv6hLWN';    // Database password
    $charset = 'utf8mb4';
    
    // DSN (Data Source Name) for the PDO connection
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    
    // PDO options for error handling
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    try {
        // Create a new PDO instance
        $pdo = new PDO($dsn, $user, $pass, $options);
    
        // SQL query
        $sql = "INSERT INTO `exchange` (`id`, `name`, `exhnage_enum`, `sell`, `buy`, `original_sell`, `original_buy`, `created_date`, `updated_date`) 
                VALUES (NULL, 'Gold', 'gold', '{$pricePerGram}', '{$pricePerGram}', '{$pricePerGram}', '{$pricePerGram}', current_timestamp(), '0000-00-00 00:00:00.000000')";
    
        // Execute the query
        $pdo->exec($sql);
    
        echo "Record inserted successfully.";
    } catch (PDOException $e) {
        // Catch and display errors
        logError("Database error: " . $e->getMessage());
    }
    
} else {
    $errorMessage = "Unable to retrieve gold price.";
    logError($errorMessage);
}

?>