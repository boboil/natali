@extends('app')
@section('content')

    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Men</a></li>
                <li class="breadcrumb-item"><a href="#">Accessories</a></li>
                <li class="breadcrumb-item active" aria-current="page">Classic Crew Neck Sweatshirt</li>
            </ol>
        </div><!-- End .container -->
    </nav>

    <div class="page-header">
        <div class="container">
            <h1>Checkout</h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->

    <div class="container">
        <form class="order-form" id="order_form">
        <div class="row row-sparse">
            <div class="col-lg-8 padding-right-lg">
                <ul class="checkout-steps">

                    <li>
                        <div class="checkout-step-shipping">
                            <h2 class="step-title">Shipping Methods</h2>




                            <div class="form-group">
                                <label>State/Province</label>
                                <div class="select-custom">
                                    <select class="form-control">
                                        <option value="na">Новая почта</option>
                                        <option value="courier">Доставка курьером</option>
                                        <option value="pickup">Самовывоз</option>
                                        <option value="overseas">За рубеж</option>
                                    </select>
                                </div><!-- End .select-custom -->
                            </div><!-- End .form-group -->


                        </div><!-- End .checkout-step-shipping -->
                    </li>

                    <li>
                        <h2 class="step-title">Shipping Address</h2>
      {{--                      <div class="form-group required-field">
                                <label>Email Address </label>
                                <div class="form-control-tooltip">
                                    <input type="email" class="form-control">
                                    <span class="input-tooltip" data-toggle="tooltip" title="We'll send your order confirmation here." data-placement="right"><i class="icon-question-circle"></i></span>
                                </div><!-- End .form-control-tooltip -->
                            </div><!-- End .form-group -->

                            <div class="form-group required-field">
                                <label>Password </label>
                                <input type="password" class="form-control">
                            </div><!-- End .form-group -->--}}

              {{--              <p>You already have an account with us. Sign in or continue as guest.</p>
                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary">LOGIN</button>
                                <a href="forgot-password.html" class="forget-pass"> Forgot your password?</a>
                            </div><!-- End .form-footer -->--}}

                            <div class="form-group required-field">
                                <label>First Name </label>
                                <input type="text" class="form-control" id="customerName" name="customerName" required>
                            </div><!-- End .form-group -->

                        <div class="form-group required-field">
                            <label>Email </label>
                            <input type="email" class="form-control" id="customerMail" name="customerMail" required>
                        </div><!-- End .form-group -->

                        <div class="form-group required-field">
                            <label>Phone Number </label>
                            <div class="form-control-tooltip">
                                <input type="tel" class="form-control" name="customerPhone" id="customerPhone" required>
                                <span class="input-tooltip" data-toggle="tooltip" title="For delivery questions." data-placement="right"><i class="icon-question-circle"></i></span>
                            </div><!-- End .form-control-tooltip -->
                        </div><!-- End .form-group -->

                            {{--<div class="form-group required-field">
                                <label>Last Name </label>
                                <input type="text" class="form-control">
                            </div>--}}<!-- End .form-group -->

                            <div class="form-group required-field">
                                <label>Street Address </label>
                                <input type="text" class="form-control" required>
                                <input type="text" class="form-control" required>
                            </div><!-- End .form-group -->

                            <div class="form-group required-field">
                                <label>City  </label>
                                <input type="text" class="form-control" required>
                            </div><!-- End .form-group -->

                            <div class="form-group">
                                <label>State/Province</label>
                                <div class="select-custom">
                                    <select class="form-control">
                                        <option value="CA">California</option>
                                        <option value="TX">Texas</option>
                                    </select>
                                </div><!-- End .select-custom -->
                            </div><!-- End .form-group -->



                        <table class="table table-step-shipping">
                                <tbody>
                                <tr>
                                    <td><input type="radio" name="payment-method" value="liq_pay"></td>
                                    <td>LiqPay </td>
                                </tr>

                                <tr>
                                    <td><input type="radio" name="payment-method" value="bank_transfer"></td>
                                    <td>Банковский перевод</td>
                                </tr>
                                <tr>
                                    <td><input type="radio" name="payment-method" value="payment_pickup"></td>
                                    <td>оплата наличными при самовывозе</td>
                                </tr>
                                </tbody>
                            </table>
                    </li>

                </ul>
            </div><!-- End .col-lg-8 -->

            <div class="col-lg-4">
                <div class="order-summary">
                    <h3>Summary</h3>

                    <h4>
                        <a data-toggle="collapse" href="#order-cart-section" class="collapsed" role="button" aria-expanded="false" aria-controls="order-cart-section">{{ Cart::getTotalQuantity() }} products in Cart</a>
                    </h4>

                    <div class="collapse" id="order-cart-section">
                        <table class="table table-mini-cart">
                            <tbody>

                            @foreach($products as $product)
                                <tr>
                                    <td class="product-col">
                                        <figure class="product-image-container">
                                            <a href="product.html" class="product-image">
                                                <img class="img-fluid" src="{{ Voyager::image($product->attributes->image) }}" alt="product" width="80" height="80">
                                            </a>
                                        </figure>
                                        <div>
                                            <h2 class="product-title">
                                                <a href="product.html">{{ $product->name }}</a>
                                            </h2>
                                            <span class="product-qty">Qty: {{ $product->quantity }}</span>
                                        </div>
                                    </td>
                                    <td class="price-col">&#8372; {{ $product->getPriceSum() }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div><!-- End #order-cart-section -->
                </div><!-- End .order-summary -->
            </div><!-- End .col-lg-4 -->
        </div><!-- End .row -->

        <div class="row row-sparse">
            <div class="col-lg-8">
                <div class="checkout-steps-action">
                    <a href="#" id="order_btn" class="btn btn-primary float-right">Купить</a>
                </div><!-- End .checkout-steps-action -->
            </div><!-- End .col-lg-8 -->
        </div><!-- End .row -->
        </form>
    </div><!-- End .container -->

    <div class="mb-6"></div><!-- margin -->

@endsection
