@extends('app')
@section('content')

<div class="page-header">
    <div class="container">
        <h1>Shopping Cart</h1>
    </div><!-- End .container -->
</div><!-- End .page-header -->

<div class="container">
    <div id="table_cart" class="row row-sparse">
            @include('cart.shopping_cart_ajax')
    </div><!-- End .row -->
</div><!-- End .container -->

<div class="mb-6"></div><!-- margin -->

@endsection
