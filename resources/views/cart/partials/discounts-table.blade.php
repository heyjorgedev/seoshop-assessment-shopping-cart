@if(!empty($discounts))

<h2>Coupons</h2>
<table class="table table-striped table-bordered cart-table">
	<thead>
		<td>Coupon</td>
		<td>Discount</td>
		@if($preview != true)
		<td style="width: 100px;"></td>
		@endif
	</thead>
	<tbody>
		@foreach($discounts as $discount)
		<tr>
			<td>{{ $discount->couponCode }}</td>

			@if($discount->isPercentage)
			<td>{{ $discount->value }}%</td>
			@else
			<td>{{ $discount->value }}€</td>
			@endif

			@if($preview != true)
			<td>
				{!! Form::open([ 'url' => action('CartController@postRemoveDiscount', [ 'id' => $discount->couponId ])]) !!}
					<button type="submit" class="btn btn-danger">Remove</button>
				{!! Form::close() !!}
			</td>
			@endif
		</tr>
		@endforeach
	</tbody>
</table>

@endif