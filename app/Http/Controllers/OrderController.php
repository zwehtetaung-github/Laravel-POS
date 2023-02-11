<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Session;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $orders = Order::all();

        $total = $orders->map(function($i) {
            return $i->total();
        })->sum();

        // return view('orders.index', compact('orders', 'total'));
        return view('orders.index', [
            'orders' => $orders
            ]);
    }

    public function detail(Request $request)
    {
        $order = Order::findOrFail($request->id);
        return view('orders.detail', [
            'orders' => $order
            ]);
    }

    public function store(OrderStoreRequest $request)
    {

        if(count((array) session('cart')) !== 0){
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'user_id' => $request->user()->id,
            ]);

            $cart = session()->get('cart');
            foreach ($cart as $id=>$item) {
                $order->items()->create([
                    'price' => $item['price'] * $item['quantity'] ,
                    'quantity' => $item['quantity'],
                    'product_id' => $item['product_id'],
                ]);
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Your Order success !');
        } else {
            return redirect()->back()->with('error', 'Error! Choose cart items.');
        }

    }

    public function show()
    {
        //
    }
}
