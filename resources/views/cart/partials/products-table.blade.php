<table class="table table-striped table-bordered cart-table">
			<thead>
				<td>Product Name</td>
				<td>Quantity</td>
				<td>Unit Price</td>
				<td>Subtotal</td>
			</thead>
			<tbody>
				@foreach($products as $product)
				<tr>
					<td>{{ $product->productName }}</td>
					<td>
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

						{!! Form::open([ 'url' => action('CartController@postRemove', ['id' => $product->productId])]) !!}
							<button type="submit" class="btn btn-danger" href="#">Remove Product</button>
						{!! Form::close() !!}

					</td>
					<td>{{ $product->unitPrice }}€</td>
					<td>{{ $product->getSubTotal() }}€</td>
				</tr>
				@endforeach
				
			</tbody>
		</table>