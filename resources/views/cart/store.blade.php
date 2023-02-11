@extends('layouts.admin')

@section('title', 'Cart List')

@section('content-header', 'Cart List')

@section('content-alert')
    <div class="alert displaynone" id="responseMsg"></div>
@endsection

@section('content-actions')
    <a href="{{ asset('admin/cart/list/') }}" class="nav-link {{ activeSegment('cart') }}">
        <span class="badge badge-primary" id="count">{{ sizeof((array) session('cart')) }}</span>
        <i class="nav-icon fas fa-cart-plus"></i>
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Remove</th>
                </thead>
                <tbody id="e-wrapper">
                    <?php $total = 0; ?>
                    @if (session('cart') && count((array) session('cart')) !== 0)
                        @foreach ( session('cart') as $id=>$items)
                            <tr data-id="{{ $id }}">
                                <td>
                                    <img src="{{ URL::asset('storage/'.$items['image']) }}" width="100" height="100" alt="Thumbnail">
                                    <span>{{ $items['name'] }}</span>
                                </td>
                                <td>
                                    {{ config('settings.currency_symbol') }} {{$items['price']}}
                                </td>
                                <td class="justify-center mt-7 mb-5 md:justify-end md:flex">
                                    <div class="relative flex flex-row w-full h-9">
                                        <input type="number" min="1" name="quantity" value="{{ $items['quantity'] }}" style="width: 3rem; height: 2rem;" class="text-center quantity">
                                        <button type="submit" class="btn btn-primary update-cart"><i class="fas fa-edit"></i></button>
                                    </div>
                                </td>
                                <td>
                                    <?php $total += $items['quantity'] * $items['price']; ?>
                                    <button class="btn btn-danger remove-cart">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="h5 text-center ">No items in Cart</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div>
                <div class="font-weight-bold" style="font-size: 1.2rem;">Total : {{ config('settings.currency_symbol') }} <span id="total_qty">{{ $total }}</span></div>
            </div>
            <div>
                <a href="{{route('cart.index')}}" class="btn btn-warning float-left">Continue shopping</a>
            </div>
            <div>
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script>
    $(document).ready(function (){
        $('.update-cart').click(function (e){
            e.preventDefault();

            $('#responseMsg').hide();

            $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });

            var id = $(this).parents("tr").attr("data-id");
            var quantity = $(this).parents("tr").find(".quantity").val();

            $.ajax({
                url: '{{ route('cart.update') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    quantity: quantity,
                },
                success:function(response){
                    if( response[0] !== parseInt(quantity) ){
                        document.getElementById("total_qty").innerHTML = response[1];
                        const message = 'Item cart is updated successfully !';
                        $("#responseMsg").removeClass("alert-danger").addClass("alert-success");
                        $('#responseMsg').html(message);
                        $('#responseMsg').show();
                    } else {
                        const message = 'Please Update item cart quantity !';
                        $("#responseMsg").removeClass("alert-success").addClass("alert-danger");
                        $('#responseMsg').html(message);
                        $('#responseMsg').show();
                    }
                }
            });
        });

        $('.remove-cart').click(function (e){

            e.preventDefault();

            $('#responseMsg').hide();

            $(this).closest('tr').remove();

            $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });

            var id = $(this).parents("tr").attr("data-id");

                $.ajax({
                    url: '{{ route('cart.remove') }}',
                    method: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                    },
                    success:function(response){
                        document.getElementById("total_qty").innerHTML = response[0];
                        document.getElementById("count").innerHTML = response[1];
                        const message = 'Item cart is removed successfully !';
                        $("#responseMsg").removeClass("alert-success").addClass("alert-danger");
                        $('#responseMsg').html(message);
                        $('#responseMsg').show();

                    }
                });
        });
    });

</script>
@endsection

