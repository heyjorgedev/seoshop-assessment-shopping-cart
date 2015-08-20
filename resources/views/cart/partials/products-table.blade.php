<table class="table table-striped table-bordered cart-table">
			<thead>
				<td>Product Name</td>
				<td>Quantity</td>
				<td>Unit Price</td>
				<td>Subtotal</td>
				@if($preview != true)
				<td style="width: 110px;"></td>
				@endif
			</thead>
			<tbody>
				@foreach($products as $product)
				<tr>
					<td>{{ $product->productName }}</td>
					<td>
						@if($preview == true)
							{{ $product->quantity }}
						@else
						<div class="form-numeric-value-group">
							<input disabled type="text" class="form-control numeric" name="quantity" value="{{ $product->quantity }}">
							<div class="buttons">

								{!! Form::open([ 'url' => action('CartController@postAdd', ['id' => $product->productId])]) !!}
									<input type="hidden" name="quantity" value="1">
									<button type="submit" class="button" href="#">+</button>
								{!! Form::close() !!}

								{!! Form::open([ 'url' => action('CartController@postRemove', ['id' => $product->productId])]) !!}
									<input type="hidden" name="quantity" value="1">
									<button type="submit" class="button" href="#">-</button>
								{!! Form::close() !!}
								
							</div>
						</div>
						@endif
					</td>
					<td>{{ $product->unitPrice }}€</td>
					<td>{{ $product->getSubTotal() }}€</td>

					@if($preview != true)
					<td>
						{!! Form::open([ 'url' => action('CartController@postRemove', ['id' => $product->productId])]) !!}
							<button type="submit" class="btn btn-danger" href="#">Remove Product</button>
						{!! Form::close() !!}
					</td>
					@endif
				</tr>
				@endforeach
				
			</tbody>
		</table>