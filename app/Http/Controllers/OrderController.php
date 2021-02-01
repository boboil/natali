<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Cart;
use Auth;

class OrderController extends Controller
{
    public function order()
    {
        return view('cart.checkout');
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

        $validator = Validator::make($request->all(), [
            'customerName' => 'required'
        ]);

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
