<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        // if (request()->wantsJson()) {
        //     return response(
        //         Customer::all()
        //     );
        // }
        // $customers = Customer::latest()->paginate(10);
        $customers = Customer::all();
        return view('customers.index')->with('customers', $customers);
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(CustomerStoreRequest $request)
    {
        $customer = Customer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'user_id' => $request->user()->id,
        ]);

        if (!$customer) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while creating customer.');
        }
        return redirect()->route('customers.index')->with('success', 'Success, your customer have been created.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;

        if (!$customer->save()) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while updating customer.');
        }
        return redirect()->route('customers.index')->with('success', 'Success, your customer have been updated.');
    }

    public function show(){
        //
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->back()->with('success', 'Success, your customer have been deleted.');
    }
}
