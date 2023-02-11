<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Response;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if($request->search){
            $products = Product::where('name',$request->search)->latest()->paginate(4);
        }else{
            $products = Product::latest()->paginate(4);
        }
        return view('cart.index', [
            'products'  => $products,
        ]);
    }

    public function addToCart($id){
        $product = Product::findOrFail($id);

        $cart = session()->get('cart', []);

        if(isset($cart[$id])){
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'product_id' => $product->id,
                'name'      => $product->name,
                'quantity'  => 1,
                'price'     => $product->price,
                'image'     => $product->image,
            ];
        }
        session()->put('cart', $cart);
        $count = count(session('cart'));
        return Response::json($count);
    }

    public function cartList(Request $request){

        return view('cart.store');
    }


    public function updateCart(Request $request){
        if($request->id && $request->quantity)
        {
            $cart = session()->get('cart');
            $qty = (int) $cart[$request->id]['quantity'];
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            $total = 0;
            foreach ($cart as $cart)
            {
                $total += $cart['quantity'] * $cart['price'];
            }
            $data = [$qty, $total];
            return Response::json($data);
        }

    }


    public function removeCart(Request $request){
        if($request->id){
            $cart = session()->get('cart');
            if(isset($cart[$request->id])){
                unset($cart[$request->id]);
                session()->put('cart', $cart);
                $cart = session()->get('cart');
                $total =0;
                foreach($cart as $cart)
                {
                    $total += $cart['quantity'] * $cart['price'];
                }
                $count = count(session('cart'));
                $data = [$total, $count];
                return Response::json($data);
            }
        }
    }

}
