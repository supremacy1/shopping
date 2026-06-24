<?php
session_start();

header('Content-Type: application/json');

require_once "db.php";
require_once __DIR__ . "/vendor/autoload.php";

use Dotenv\Dotenv;

// Load variables from .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['reference'])) {
    echo json_encode([
        "status" => "error",
        "message" => "No payment reference provided"
    ]);
    exit;
}

$reference = trim($data['reference']);

if (empty($reference)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid payment reference"
    ]);
    exit;
}

$secretKey = $_ENV['PAYSTACK_SECRET_KEY'] ?? '';

if (empty($secretKey)) {
    echo json_encode([
        "status" => "error",
        "message" => "Paystack configuration is missing"
    ]);
    exit;
}

// Verify transaction directly with Paystack
$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . $secretKey,
        "Content-Type: application/json"
    ],
    CURLOPT_TIMEOUT => 30
]);

$response = curl_exec($ch);
$curlError = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

if ($curlError) {
    echo json_encode([
        "status" => "error",
        "message" => "Unable to verify payment. Please try again."
    ]);
    exit;
}

$result = json_decode($response, true);

if (
    $httpCode !== 200 ||
    empty($result['status']) ||
    empty($result['data']) ||
    ($result['data']['status'] ?? '') !== 'success'
) {
    echo json_encode([
        "status" => "error",
        "message" => $result['message'] ?? "Payment verification failed"
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Confirm that this reference belongs to an order in your database
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT id, total_amount, payment_status
    FROM orders
    WHERE payment_reference = ?
    LIMIT 1
");

$stmt->bind_param("s", $reference);
$stmt->execute();

$orderResult = $stmt->get_result();
$order = $orderResult->fetch_assoc();

$stmt->close();

if (!$order) {
    echo json_encode([
        "status" => "error",
        "message" => "Order not found"
    ]);
    exit;
}

if ($order['payment_status'] === 'paid') {
    echo json_encode([
        "status" => "success",
        "message" => "Payment was already verified"
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Confirm amount paid matches order amount
|--------------------------------------------------------------------------
| Paystack returns amount in kobo.
| Example: ₦5,000 = 500000 kobo
*/

$paystackAmount = (int) ($result['data']['amount'] ?? 0);
$orderAmountInKobo = (int) round($order['total_amount'] * 100);

if ($paystackAmount !== $orderAmountInKobo) {
    echo json_encode([
        "status" => "error",
        "message" => "Payment amount does not match the order amount"
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Mark order as paid
|--------------------------------------------------------------------------
*/

$updateStmt = $conn->prepare("
    UPDATE orders
    SET payment_status = 'paid'
    WHERE id = ?
");

$updateStmt->bind_param("i", $order['id']);
$updateStmt->execute();
$updateStmt->close();

// Clear cart only after successful verified payment
unset($_SESSION['cart']);

echo json_encode([
    "status" => "success",
    "message" => "Payment verified successfully"
]);