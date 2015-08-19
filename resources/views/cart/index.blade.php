@extends('layouts.main')

@section('content')
	<h1 style="margin-bottom: 30px;">My Cart</h1>
	
	@if(empty($cart->products))
		<p>You have no items in your shopping cart.</p>
	@else
		
		@include('cart.partials.products-table', [ 'products' => $cart->products])
		
		@include('cart.partials.discounts-table', [ 'discounts' => $cart->discounts])
		
		<h2 style="text-align: right;">Total: {{ $cart->total }}€</h2>

		<div> <!-- TODO: Coupons -->
			{!! Form::open([ 'url' => '']) !!}
				<input class="form-control" type="text">
				<button class="btn btn-default" type="submit">Add Coupon</button>
			{!! Form::close() !!}
		</div>

		<div>
			<a href="{{ action('CartController@getCheckout') }}" class="btn btn-lg btn-success">Checkout</a>
		</div>
	@endif
@stop

@section('scripts')
<script>
	$('.numeric').numeric(1);
</script>
@stop