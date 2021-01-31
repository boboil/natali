<section class="container pb-3 mb-1">
    <h2 class="section-title ls-n-10 text-center pb-2 m-b-4">Новые товары</h2>

    <div class="row py-4">
        @foreach($new_products as $new_product)
        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
            <div class="product-default inner-quickview inner-icon">
                <figure>
                    <a href="{{route('product', $new_product->slug)}}">
                        <img src="{{Voyager::image($new_product->thumbnail('product'))}}" alt="{{ $new_product->name }}">
                    </a>
                    <div class="label-group">
                        <span class="product-label label-sale">-30%</span>
                    </div>
                    <div class="btn-icon-group">
                        <div class="btn-icon-group">
                            <button data-content="{{$new_product->id}}" class="btn-icon btn-add-cart add-cart"><i class="icon-shopping-cart"></i></button>
                    </div>
                    </div>
                    <a href="{{route('product.ajax', $new_product->id)}}" class="btn-quickview" title="Quick View">Quick View</a>
                </figure>
                <div class="product-details">
                    <div class="category-wrap">
                        <div class="category-list">
                            <a href="category.html" class="product-category">category</a>
                        </div>
                    </div>
                    <h3 class="product-title">
                        <a href="{{route('product', $new_product->slug)}}">{{ $new_product->name }}</a>
                    </h3>
                    <div class="ratings-container">
                        <div class="product-ratings">
                            <span class="ratings" style="width:100%"></span><!-- End .ratings -->
                            <span class="tooltiptext tooltip-top"></span>
                        </div><!-- End .product-ratings -->
                    </div><!-- End .product-container -->
                    <div class="price-box">
                        @if($new_product->old_price)
                            <span class="old-price">&#8372 {{ $new_product->old_price }}</span>
                        @endif
                        <span class="product-price">&#8372 {{ $new_product->price }}</span>
                    </div><!-- End .price-box -->
                </div><!-- End .product-details -->
            </div>
        </div>
        @endforeach
    </div>

    <hr class="mt-3 mb-6">

    <div class="row feature-boxes-container pt-2">
        <div class="col-sm-6 col-lg-3">
            <div class="feature-box feature-box-simple text-center">
                <i class="icon-earphones-alt"></i>

                <div class="feature-box-content">
                    <h3 class="text-uppercase">Customer Support</h3>
                    <h5>Need Assistence?</h5>

                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis nec vestibulum magna, et dapib.</p>
                </div><!-- End .feature-box-content -->
            </div><!-- End .feature-box -->
        </div><!-- End .col-lg-3 -->

        <div class="col-sm-6 col-lg-3">
            <div class="feature-box feature-box-simple text-center">
                <i class="icon-credit-card"></i>

                <div class="feature-box-content">
                    <h3 class="text-uppercase">Secured Payment</h3>
                    <h5>Safe & Fast</h5>

                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis nec vestibulum magna, et dapibus lacus. Lorem ipsum dolor sit amet.</p>
                </div><!-- End .feature-box-content -->
            </div><!-- End .feature-box -->
        </div><!-- End .col-lg-3 -->

        <div class="col-sm-6 col-lg-3">
            <div class="feature-box feature-box-simple text-center">
                <i class="icon-action-undo"></i>

                <div class="feature-box-content">
                    <h3 class="text-uppercase">Free Returns</h3>
                    <h5>Easy & Free</h5>

                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis nec vestibulum magna, et dapib.</p>
                </div><!-- End .feature-box-content -->
            </div><!-- End .feature-box -->
        </div><!-- End .col-lg-3 -->

        <div class="col-sm-6 col-lg-3">
            <div class="feature-box feature-box-simple text-center">
                <i class="icon-shipping"></i>

                <div class="feature-box-content">
                    <h3 class="text-uppercase">Free Shipping</h3>
                    <h5>Orders Over $99</h5>

                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis nec vestibulum magna, et dapib.</p>
                </div><!-- End .feature-box-content -->
            </div><!-- End .feature-box -->
        </div><!-- End .col-lg-3 -->
    </div><!-- End .row .feature-boxes-container-->
</section>


<!-- Modal -->
<div class="modal bd-example-modal-lg fade" id="modal-global">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div>
    </div>
</div>
