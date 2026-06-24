<?php
include "db.php";

// Get raw input
$input = file_get_contents("php://input");
$event = json_decode($input);

// Verify event exists
if ($event && $event->event == "charge.success") {

    $reference = $event->data->reference;
    $amount = $event->data->amount / 100;
    $email = $event->data->customer->email;

    // SECURITY CHECK: confirm from Paystack server
    $secretKey = isset($_ENV['PAYSTACK_SECRET_KEY']) ? $_ENV['PAYSTACK_SECRET_KEY'] : "YOUR_SECRET_KEY";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.paystack.co/transaction/verify/" . $reference);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $secretKey"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($result['data']['status'] == "success") {

        // mark order as paid
        $stmt = $conn->prepare("UPDATE orders SET payment_status='paid', payment_reference=? WHERE payment_reference=?");
        $stmt->bind_param("ss", $reference, $reference);
        $stmt->execute();
    }
}