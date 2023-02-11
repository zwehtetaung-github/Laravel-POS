@extends('layouts.admin')

@section('title', 'Open POS')

@section('content-header', 'POS')

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
<div class="card-deck">
    <div class="card">
        <div class="card-body">
                <form action="{{ route('cart.index') }}" class=" me-5">
                    <input type="search" placeholder="Search product..." name="search" class="form-control mb-3">
                </form>
                    <div class="row mb-3">
                        @foreach ($products as $product)
                            <div class="col-6 col-lg-3 card" style="width: 18rem;">
                                <img src="{{ URL::asset('storage/'. $product->image) }}" class="card-img-top img-fluid" alt="Card image cap" style="width: 18rem; height: 20rem;" />
                                <div class="card-body">
                                    <h3 class="card-title"><strong>{{ $product->name }}</strong></h3>
                                    <div class="card-text">${{ $product->price }}</div>
                                    <a href="javascript:void(0)" data-url="{{ route('cart.store', $product->id) }}" class="btn btn-warning add-cart" id="add-cart">Add to cart</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
            {{$products->links()}}
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function(){
        $('.add-cart').click(function (e){
            e.preventDefault();

            $('#responseMsg').hide();

            var userurl = $(this).data('url');

            $.ajax({
                url: userurl,
                method: "GET",
                dataType: 'json',
                success:function(response){
                    document.getElementById("count").innerHTML = response;
                    const message = 'Product is added to Cart successfully !';
                    $('#responseMsg').addClass("alert-success");
                    $('#responseMsg').html(message);
                    $('#responseMsg').show();
                }
            });

        });

    });
</script>
@endsection

