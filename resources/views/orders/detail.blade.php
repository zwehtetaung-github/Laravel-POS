@extends('layouts.admin')

@section('title', 'Orders List')
@section('content-header', 'Order Detail')
@section('content-actions')
    <a href="{{route('orders.index')}}"><i class='fas fa-arrow-left' style='font-size:25px;color:rgb(89, 83, 83)'></i></a>
@endsection
@section('content')
<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders->items as $item)
                    <tr>
                        <td>{{ $item->order_id }}</td>
                        <td>
                            <img src="{{ URL::asset('storage/'.$item->product->image) }}" width="50" height="50" alt="Thumbnail">
                            <span>{{ $item->product->name }} </span>
                        </td>
                        <td>{{ config('settings.currency_symbol') }} {{ $item->product->price }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ config('settings.currency_symbol') }} {{ $item->subTotal() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center font-weight-bold" style="font-size: 1.1rem" > Total = {{ config('settings.currency_symbol') }} {{ $orders->formattedTotal() }}</div>
    </div>
</div>
@endsection

