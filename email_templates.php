<?php

function get_customer_receipt_html($order_details, $order_items) {
    $currency_symbol = ($order_details['currency'] === 'USD') ? '$' : '₦';
    $item_rows = '';
    foreach ($order_items as $item) {
        $item_total = ($item['price_naira'] ?? $item['price']) * $item['qty'];
        $item_rows .= "<tr>
            <td style='padding: 10px; border-bottom: 1px solid #eee;'>" . htmlspecialchars($item['name']) . " (x" . htmlspecialchars($item['qty']) . ")</td>
            <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right;'>" . $currency_symbol . number_format($item_total, 2) . "</td>
        </tr>";
    }

    $html = "
    <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: auto; border: 1px solid #ddd; border-radius: 10px; padding: 20px;'>
        <h1 style='color: #4F46E5; text-align: center;'>Thank You for Your Order!</h1>
        <p>Hi " . htmlspecialchars($order_details['customer_name']) . ",</p>
        <p>We've received your order and will process it shortly. Here are the details:</p>
        
        <div style='background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;'>
            <h3 style='margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px;'>Order Summary</h3>
            <p><strong>Order Reference:</strong> " . htmlspecialchars($order_details['payment_reference']) . "</p>
            <p><strong>Order Date:</strong> " . date('F j, Y') . "</p>
        </div>

        <table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>
            <thead>
                <tr>
                    <th style='padding: 10px; background-color: #f2f2f2; text-align: left;'>Item</th>
                    <th style='padding: 10px; background-color: #f2f2f2; text-align: right;'>Price</th>
                </tr>
            </thead>
            <tbody>
                " . $item_rows . "
            </tbody>
            <tfoot>
                <tr>
                    <td style='padding: 10px; text-align: right; font-weight: bold;'>Total:</td>
                    <td style='padding: 10px; text-align: right; font-weight: bold;'>" . $currency_symbol . number_format($order_details['total_amount'], 2) . "</td>
                </tr>
            </tfoot>
        </table>

        <p>We appreciate your business. If you have any questions, please don't hesitate to contact us.</p>
        <p>Best regards,<br>The Desamall Team</p>
    </div>
    ";
    return $html;
}

function get_admin_notification_html($order_details, $order_items, $address_details) {
    $currency_symbol = ($order_details['currency'] === 'USD') ? '$' : '₦';
    $item_rows = '';
    foreach ($order_items as $item) {
        $item_rows .= "<li>" . htmlspecialchars($item['name']) . " (Qty: " . htmlspecialchars($item['qty']) . ")</li>";
    }

    $html = "
    <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <h1 style='color: #DC2626;'>New Order Notification</h1>
        <p>A new order has been placed on Desamall.</p>
        
        <h2>Order Details</h2>
        <ul>
            <li><strong>Reference:</strong> " . htmlspecialchars($order_details['payment_reference']) . "</li>
            <li><strong>Total Amount:</strong> " . $currency_symbol . number_format($order_details['total_amount'], 2) . " (" . $order_details['currency'] . ")</li>
            <li><strong>Payment Status:</strong> " . htmlspecialchars($order_details['payment_status']) . "</li>
        </ul>

        <h2>Customer Details</h2>
        <ul>
            <li><strong>Name:</strong> " . htmlspecialchars($order_details['customer_name']) . "</li>
            <li><strong>Email:</strong> " . htmlspecialchars($order_details['email']) . "</li>
            <li><strong>Phone:</strong> " . htmlspecialchars($address_details['phone_number']) . "</li>
        </ul>

        <h2>Shipping Address</h2>
        <p>
            " . htmlspecialchars($address_details['address']) . "<br>
            " . htmlspecialchars($address_details['city']) . "<br>
            " . htmlspecialchars($address_details['country']) . "
        </p>

        <h2>Items Ordered</h2>
        <ul>
            " . $item_rows . "
        </ul>
    </div>
    ";
    return $html;
}

?>