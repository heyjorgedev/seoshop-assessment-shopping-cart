@extends('layouts.main')

@section('content')
<a href="{{ action('ProductsController@getIndex') }}" class="btn btn-default" role="button">&lt; Back to Product List</a>

<h1 style="margin-bottom: 30px;">{{ $product->title }}</h1>

<div class="row">
	<div class="col-sm-6 col-md-4">
		@include('products.partials.product-image', [ 'url' => $product->image_url, 'title' => $product->title ])
	</div>
	<div class="col-sm-6 col-md-8">
		<div style="height:300px;" class="description well well-lg">
			<div><b>Description</b></div>
			<p>{{ $product->description }}</p>
		</div>
		<div>
			
		</div>
		<div>
			<div>
				<h3>Unit Price: <b>{{ $product->price }}€</b></h3>
			</div>
			{!! Form::open([ 'url' => action('CartController@postAdd', ['id' => $product->id])]) !!}
			<div class="form-numeric-value-group">
				<input type="text" class="form-control numeric" disabled id="quantity" name="quantity" value="1">
				<div class="buttons">
					<a class="button" onclick="changeQuantity('up');" href="#up">+</a>
					<a class="button" onclick="changeQuantity('down');" href="#down">-</a>
				</div>
			</div>
			<button style="float:left;margin-left:10px;" type="submit" class="btn btn-success" href="#">Add to Cart</button>
			{!! Form::close() !!}
		</div>
	</div>
</div> 
@stop

@section('scripts')
<script>
	$('.description').textTailor({
		fit: false,
		ellipsis: true,
	});

	function changeQuantity(variant)
	{
		debugger;
		var el = $('#quantity');
		var value = parseInt($(el).attr('value'));

		if(variant === 'up')
		{
			$(el).attr('value', value + 1);
		}
		else if(variant === 'down')
		{
			if(value <= 1)
			{
				$(el).attr('value', 1);
				return;
			}

			$(el).attr('value', value - 1);
		}
	}
</script>
@stop
