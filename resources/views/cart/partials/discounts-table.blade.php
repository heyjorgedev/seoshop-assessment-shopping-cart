@if(!empty($discounts))

<h2>Coupons</h2>
<table class="table table-striped table-bordered cart-table">
	<thead>
		<td>Coupon</td>
		<td>Discount</td>
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

		</tr>
		@endforeach
	</tbody>
</table>

@endif