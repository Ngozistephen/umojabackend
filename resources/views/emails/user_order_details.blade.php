<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
</head>
<body>
    <h1>Order Details</h1>
    <p>Dear {{ $user->name }},</p>
    <p>Thank you for your order. Here are your order details:</p>
    <p>Order Number: {{ $orderNumber }}</p>
    <p>Tracking Number: {{ $trackingNumber }}</p>
    <p>Order Total: {{ $order->total_amount }}</p>
    <!-- Add more details as needed -->
</body>
</html>
