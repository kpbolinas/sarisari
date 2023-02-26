Order Notification Mail!

Customer Information:
Email: {{ $data['user']['email'] }}
Username: {{ $data['user']['username'] }}
Name: {{ $data['user']['name'] }}

Order Details:
QUANTITY | NAME | PRICE
@foreach ($data['products']['items'] as $product)
{{ $product['quantity'] }} | {{ $product['item']['name'] }} | P {{ $product['total'] }}
@endforeach
TOTAL PRICE | P {{ $data['products']['total_price'] }}