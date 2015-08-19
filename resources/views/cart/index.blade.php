@extends('layouts.main')

@section('content')

	@if(!$errors->isEmpty())
	<div class="alert alert-danger alert-dismissible" role="alert">
		
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		
		{{ $errors->first('message') }}

	</div>
	@endif

	<h1 style="margin-bottom: 30px;">My Cart</h1>

	@if(empty($cart->products))
		<p>You have no items in your shopping cart.</p>
	@else
		
		@include('cart.partials.products-table', [ 'products' => $cart->products])
		
		@include('cart.partials.discounts-table', [ 'discounts' => $cart->discounts])
		
		<div> <!-- TODO: Coupons -->
			{!! Form::open([ 'url' => action('CartController@postAddDiscount') , 'method' => 'POST']) !!}
				<input class="form-control" name="code" style="float:left;width:200px;text-transform: uppercase;" placeholder="" type="text">
				<button class="btn btn-primary" style="float:left; margin-left:-1px;" type="submit">Add Coupon</button>
			{!! Form::close() !!}
		</div>

		<div style="clear:both;"></div>

		<h2 style="text-align: left;">Total: {{ $cart->total }}€</h2>
		
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