<div class="col-lg-8 padding-right-lg">
    <div class="cart-table-container">
        <table class="table table-cart">
            <thead>
            <tr>
                <th class="product-col">Product</th>
                <th class="price-col">Price</th>
                <th class="qty-col">Qty</th>
                <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr class="product-row">
                    <td class="product-col">
                        <figure class="product-image-container">
                            <a href="product.html" class="product-image">
                                <img src="{{ Voyager::image($product->attributes->image) }}" alt="product">
                            </a>
                        </figure>
                        <h2 class="product-title">
                            <a href="product.html">{{ $product->name }}</a>
                        </h2>
                    </td>
                    <td>&#8372 {{ $product->price }}</td>
                    <td>


                        <div class="d-inline-flex">
                            <button class="btn btn-sm cart-btn minus" data-content="{{ $product->id }}" data-action="decrease">-</button>
                            <input class="quantity w-25" min="0" name="quantity" value="{{ $product->quantity }}" type="number" disabled>
                            <button class="btn btn-sm cart-btn plus" data-content="{{ $product->id }}" data-action="increase">+</button>
                        </div>
                    </td>
                    <td>&#8372; {{ $product->getPriceSum() }}</td>
                </tr>
                <tr class="product-action-row">
                    <td colspan="4" class="clearfix">
                        <div class="float-left">
                            <a href="#" class="btn-move">Move to Wishlist</a>
                        </div><!-- End .float-left -->

                        <div class="float-right">
                            <a href="#" title="Remove product" class="btn-remove icon-cancel" data-content="{{ $product->id }}"><span class="sr-only">Remove</span></a>
                        </div><!-- End .float-right -->
                    </td>
                </tr>
            @endforeach
            </tbody>

            <tfoot>
            <tr>
                <td colspan="4" class="clearfix">
                    <div class="float-left">
                        <a href="category.html" class="btn btn-outline-secondary">Continue Shopping</a>
                    </div><!-- End .float-left -->

                    <div class="float-right">
                        <a href="#" class="btn btn-outline-secondary btn-clear-cart">Clear Shopping Cart</a>
                        <a href="#" class="btn btn-outline-secondary btn-update-cart">Update Shopping Cart</a>
                    </div><!-- End .float-right -->
                </td>
            </tr>
            </tfoot>
        </table>
    </div><!-- End .cart-table-container -->

    <div class="cart-discount">
        <h4>Apply Discount Code</h4>
        <form action="#">
            <div class="input-group">
                <input type="text" class="form-control form-control-sm" placeholder="Enter discount code"  required>
                <div class="input-group-append">
                    <button class="btn btn-sm btn-primary" type="submit">Apply Discount</button>
                </div>
            </div><!-- End .input-group -->
        </form>
    </div><!-- End .cart-discount -->
</div><!-- End .col-lg-8 -->

<div class="col-lg-4">
    <div class="cart-summary">
        <h3>Summary</h3>

        <h4>
            <a data-toggle="collapse" href="#total-estimate-section" class="collapsed" role="button" aria-expanded="false" aria-controls="total-estimate-section">Estimate Shipping and Tax</a>
        </h4>

        <div class="collapse" id="total-estimate-section">
            <form action="#">
                <div class="form-group form-group-sm">
                    <label>Country</label>
                    <div class="select-custom">
                        <select class="form-control form-control-sm">
                            <option value="USA">United States</option>
                            <option value="Turkey">Turkey</option>
                            <option value="China">China</option>
                            <option value="Germany">Germany</option>
                        </select>
                    </div><!-- End .select-custom -->
                </div><!-- End .form-group -->

                <div class="form-group form-group-sm">
                    <label>State/Province</label>
                    <div class="select-custom">
                        <select class="form-control form-control-sm">
                            <option value="CA">California</option>
                            <option value="TX">Texas</option>
                        </select>
                    </div><!-- End .select-custom -->
                </div><!-- End .form-group -->

                <div class="form-group form-group-sm">
                    <label>Zip/Postal Code</label>
                    <input type="text" class="form-control form-control-sm">
                </div><!-- End .form-group -->

                <div class="form-group form-group-custom-control">
                    <label>Flat Way</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="flat-rate">
                        <label class="custom-control-label" for="flat-rate">Fixed $5.00</label>
                    </div><!-- End .custom-checkbox -->
                </div><!-- End .form-group -->

                <div class="form-group form-group-custom-control">
                    <label>Best Rate</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="best-rate">
                        <label class="custom-control-label" for="best-rate">Table Rate $15.00</label>
                    </div><!-- End .custom-checkbox -->
                </div><!-- End .form-group -->
            </form>
        </div><!-- End #total-estimate-section -->

        <div id="total-right">
            <table class="table table-totals" id="total_cart">
                <tbody>
                <tr>
                    <td>Subtotal</td>
                    <td>&#8372;  {{ Cart::getTotal() }}</td>
                </tr>

                <tr>
                    <td>Tax</td>
                    <td>$0.00</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td>Order Total</td>
                    <td>&#8372; {{ Cart::getTotal() }}</td>
                </tr>
                </tfoot>
            </table>
        </div>


        <div class="checkout-methods">
            <a href="{{route('checkout')}}" class="btn btn-block btn-sm btn-primary">Go to Checkout</a>
            <a href="#" class="btn btn-link btn-block">Check Out with Multiple Addresses</a>
        </div><!-- End .checkout-methods -->
    </div><!-- End .cart-summary -->
</div><!-- End .col-lg-4 -->

