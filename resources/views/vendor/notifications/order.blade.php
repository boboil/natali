@component('mail::message')

@component('mail::table')
| | Название  | Кол-во | Цена | Всего |
| :---: | ------------- | :----: | :----: | :----: |
@foreach($cart_products as $product)
| <img src="{{ Voyager::image($product->attributes->image) }}" alt="" width="40" /> | {{ $product->name }} <br> @if ($product->attributes->type == 'service') {{ $product->attributes->area }} @endif | {{ $product->quantity }} |  &#8372; {{ $product->price }} | &#8372; {{ $product->getPriceSum() }} |
@endforeach
@endcomponent

@component('mail::panel')
Полная сумма заказа: &#8372; {{ Cart::getTotal() }}
@endcomponent

@component('mail::table')
|  |  |
| ----- | :-------------- |
| ФИО | {{ $data["customer_name"] }} |
| Тел. | {{ $data["customer_phone"] }} |
| Email | {{ $data["customer_mail"] }} |
| Доставка | {{ $data["delivery"] }} |
| Оплата | {{ $data["payment"] }} |
@endcomponent


@endcomponent
