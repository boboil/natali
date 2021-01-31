<div id="drop-cart">
    <div class="dropdownmenu-wrapper">

        @if(count($cart_products) > 0)

            <div class="dropdown-cart-header">
                <span>{{ Cart::getTotalQuantity() }} Items</span>

                <a href="{{route('shopping.cart')}}" class="float-right">View Cart</a>
            </div><!-- End .dropdown-cart-header -->

            <div class="dropdown-cart-products">
                @foreach($cart_products as $cart_product)
                    <div class="product">
                        <div class="product-details">
                            <h4 class="product-title">
                                <a href="#">{{ $cart_product->name }}</a>
                            </h4>

                            <span class="cart-product-info">
                                                        <span class="cart-product-qty">{{ $cart_product->quantity }}</span>
                                                        x {{ $cart_product->price }}
                                                    </span>
                        </div><!-- End .product-details -->

                        <figure class="product-image-container">
                            <a href="product.html" class="product-image">
                                <img class="img-fluid" src="{{ Voyager::image($cart_product->attributes->image) }}" alt="product" width="80" height="80">
                            </a>
                            <a href="#" data-content="{{ $cart_product->id }}" class="btn-remove icon-cancel" title="Remove Product"></a>
                        </figure>
                    </div><!-- End .product -->
                @endforeach
            </div><!-- End .cart-product -->

            <div class="dropdown-cart-total">
                <span>Total</span>

                <span class="cart-total-price float-right">{{ Cart::getSubTotal() }}</span>
            </div><!-- End .dropdown-cart-total -->

            <div class="dropdown-cart-action">
                <a href="{{ route('checkout') }}" class="btn btn-primary btn-block">Checkout</a>
            </div><!-- End .dropdown-cart-total -->

        @endif

    </div><!-- End .dropdownmenu-wrapper -->
</div>


