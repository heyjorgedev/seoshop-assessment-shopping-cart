


	@if(empty($cart->products))
		<p>You have no items in your shopping cart.</p>
	@else
		
		@include('cart.partials.products-table', [ 'products' => $cart->products])
		
		@include('cart.partials.discounts-table', [ 'discounts' => $cart->discounts])
		
		@if($preview != true)
		<div>
			{!! Form::open([ 'url' => action('CartController@postAddDiscount') , 'method' => 'POST']) !!}
				<input class="form-control" name="code" style="float:left;width:200px;text-transform: uppercase;" placeholder="" type="text">
				<button class="btn btn-primary" style="float:left; margin-left:-1px;" type="submit">Add Coupon</button>
			{!! Form::close() !!}
		</div>
		@endif

		<div style="clear:both;"></div>

		<h2 style="text-align: left;">Total: {{ $cart->total }}€</h2>
		
		@if($preview != true)
		<div>
			<a href="{{ action('CartController@getCheckout') }}" class="btn btn-lg btn-success">Checkout</a>
		</div>
		@endif
		
	@endif