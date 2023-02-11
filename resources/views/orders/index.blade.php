@extends('layouts.admin')

@section('title', 'Orders List')
@section('content-header', 'Order List')
@section('content-actions')
    <a href="{{route('cart.index')}}" class="btn btn-primary">Open POS</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Total</th>
                    <th>Order Date</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                <tr>
                    <td>{{$order->id}}</td>
                    <td>{{$order->getCustomerName()}}</td>
                    <td>{{ config('settings.currency_symbol') }} {{$order->formattedTotal()}}</td>
                    <td>{{$order->created_at}}</td>
                    <td><a href="{{ route('orders.detail',$order->id) }}">View details</a></td>
                </tr>
                @empty
                    <tr>
                        <td class="h5 text-center">No orders list</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

