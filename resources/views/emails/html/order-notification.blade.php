<!DOCTYPE html>
<html>

<head>
    <title>Sari Sari Store - Order Notification</title>
</head>

<body>
    <h2>ORDER NOTIFICATION MAIL!</h2>
    <p>
        CUSTOMER INFORMATION:
        <br>
        Email: {{ $data['user']['email'] }}
        <br>
        Username: {{ $data['user']['username'] }}
        <br>
        Name: {{ $data['user']['name'] }}
    </p>
    <p>ORDER DETAILS:</p>
    <table>
        <thead>
            <tr>
                <th>QUANTITY</th>
                <th>NAME</th>
                <th>PRICE</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['products']['items'] as $product)
            <tr>
                <td>{{ $product['quantity'] }}</td>
                <td>{{ $product['item']['name'] }}</td>
                <td>P {{ $product['total'] }}</td>
            </tr>
            @endforeach
            <tr>
                <td></td>
                <td>TOTAL PRICE</td>
                <td>P {{ $data['products']['total_price'] }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>