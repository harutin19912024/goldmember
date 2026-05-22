<?php
/**
 * Cron-ready metal price fetcher.
 * Pulls latest XAU/XAG/XPT/XPD prices in USD from metalpriceapi.com and inserts
 * one row per metal into metal_price_real. The admin "Set Metal Prices" page
 * reads from this table (latest row per metal_id).
 *
 * Schedule example (daily at 09:00):
 *   0 9 * * *  /usr/bin/php /var/www/html/goldmember/updateExchange.php >> /var/www/html/goldmember/error.log 2>&1
 */

const API_KEY = '328dbc54ea99eeba3e5ca4e64604cc19';
const API_URL = 'https://api.metalpriceapi.com/v1/latest';
const TROY_OUNCE_GRAMS = 31.1035;
const METALS = [1 => 'XAU', 2 => 'XAG', 3 => 'XPT', 4 => 'XPD'];

$dbConfig = [
    'host'    => getenv('DB_HOST') ?: 'localhost',
    'db'      => getenv('DB_NAME') ?: 'gssamru_goldmember',
    'user'    => getenv('DB_USER') ?: 'gssamru_autoimport',
    'pass'    => getenv('DB_PASS') ?: 'bGtVSaf2rv6hLWN',
    'charset' => 'utf8mb4',
];

$logFile = __DIR__ . '/error.log';
function logLine(string $msg, string $logFile): void
{
    file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL, FILE_APPEND);
}

logLine('updateExchange: start', $logFile);

$url = API_URL . '?api_key=' . API_KEY . '&base=USD&currencies=' . implode(',', METALS);
$ctx = stream_context_create(['http' => ['timeout' => 10]]);
$raw = @file_get_contents($url, false, $ctx);
if ($raw === false) {
    logLine('updateExchange: API request failed (network)', $logFile);
    exit(1);
}

$data = json_decode($raw, true);
if (empty($data['rates']) || !is_array($data['rates'])) {
    logLine('updateExchange: unexpected response — ' . substr($raw, 0, 300), $logFile);
    exit(1);
}

$ts = $data['timestamp'] ?? time();

try {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['db']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    $stmt = $pdo->prepare(
        'INSERT INTO metal_price_real (metal_id, currency_id, created_date, request_data, request_error)
         VALUES (:metal_id, 1, NOW(), :payload, NULL)'
    );

    $reported = [];
    foreach (METALS as $metalId => $symbol) {
        if (empty($data['rates'][$symbol])) {
            logLine("updateExchange: rate missing for {$symbol}", $logFile);
            continue;
        }
        // rate = ounces per 1 USD  →  price per oz = 1 / rate
        $pricePerOz = 1.0 / (float)$data['rates'][$symbol];
        $pricePerGram = round($pricePerOz / TROY_OUNCE_GRAMS, 4);
        $payload = json_encode([
            'timestamp'        => $ts,
            'metal'            => $symbol,
            'currency'         => 'USD',
            'price'            => $pricePerOz,
            'prev_close_price' => $pricePerOz,
            'ask'              => $pricePerOz,
            'bid'              => $pricePerOz,
            'price_gram_24k'   => $pricePerGram,
        ]);
        $stmt->execute([':metal_id' => $metalId, ':payload' => $payload]);
        $reported[$symbol] = round($pricePerOz, 2);
    }

    logLine('updateExchange: ok — ' . json_encode($reported), $logFile);
    echo "Inserted: " . json_encode($reported) . PHP_EOL;
} catch (PDOException $e) {
    logLine('updateExchange: DB error — ' . $e->getMessage(), $logFile);
    exit(1);
}
