<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Cart;
use App\Models\Order;
use Auth;

class CartController extends Controller
{
    public function add(Request $request){
        $product = Product::find($request->product_id);
        Cart::add($product->id, $product->translate()->name, $product->price, 1, array('image' => $product->image, 'sku' => $product->sku));
/*        if($request->ajax()){
            $cart_products = Cart::getContent()->sortByDesc('id');
            return response()->view('cart.dropCart', compact('cart_products'));
        }*/
    }

    public function remove(Request $request){

        Cart::remove($request->product_id);

        if($request->ajax()){
            $products = Cart::getContent()->sortByDesc('id');
            if (Cart::isEmpty()) {
                return response()->json(array('msg' => 'empty'), 200);
            }
            return response()->view('cart.shopping_cart_ajax', compact('products'));
        }

    }

    public function update(Request $request){

        $product = Product::find($request->product_id);
        if ($request->cart_action == 'increase') {
            Cart::update($product->id, array('quantity' => 1));
            if($request->ajax()){
                $products = Cart::getContent()->sortByDesc('id');
                return response()->view('cart.shopping_cart_ajax', compact('products'));
            }
        }

        if ($request->cart_action == 'decrease') {
            Cart::update($product->id, array('quantity' => -1));
            if($request->ajax()){
                $products = Cart::getContent()->sortByDesc('id');
                return response()->view('cart.shopping_cart_ajax', compact('products'));
            }
        }
    }


    public function shopping(Request $request){
        $products = Cart::getContent()->sortByDesc('id');
        return view('cart.shopping_cart', compact('products'));
    }


    public function checkout(Request $request){
        $products = Cart::getContent()->sortByDesc('id');
        return view('cart.checkout', compact('products'));
    }


    public function order()
    {
        return view('cart.order');
    }

    public function ordersend(Request $request)
    {

/*        $data = array(
            'customer_name'=>$request->customerName,
            'customer_mail'=>$request->customerMail,
            'customer_phone'=>$request->customerPhone,
            'delivery'=>$delivery,
            'payment'=>$paymentText

        );

        $validator = Validator::make($request->all(), [
            'customerName' => 'required'
        ]);*/

        $order = new Order;
        $order->customer_name = $request->customerName;
        $order->customer_mail = $request->customerMail;
        $order->customer_phone = $request->customerPhone;
/*        $order->delivery = $delivery;
        $order->payment = $paymentText;*/
        $order->user_id = $request->user_id;
        $order->price = Cart::getTotal();
        $order->body = Cart::getContent();



        if ($validator->passes()) {

            $order->save();

            $resultLink = '/';

           /* Mail::to('antialkogol333@gmail.com')->send(new OrderClass($data));*/

            Cart::clear();

            return response()->json([
                'success',
                'redirect' => $resultLink,
            ]);
        }


        return response()->json(['error'=>$validator->errors()->all()]);

    }



}
