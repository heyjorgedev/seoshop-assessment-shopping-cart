@extends('layouts.main')

@section('content')
<div>
<a href="{{ action('CartController@getIndex') }}" class="btn btn-default" role="button">&lt; Back to Shopping Cart</a>
</div>

<h1>Review your order</h1>
<div>
	@include('cart.partials.cart', ['preview' => true])
</div>

{!! Form::open([]) !!}
<h1>Customer Details</h1>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label for="inputEmail3" class="control-label">Email</label>
			<input type="email" class="form-control" id="inputEmail3">
		</div>
		<div class="form-group">
			<label for="inputEmail3" class="control-label">Title</label>
			<select class="form-control">
				<option>Mr</option>
				<option>Mrs</option>
				<option>Miss</option>
			</select>
		</div>
		<div class="form-group">
			<label for="inputEmail3" class="control-label">First Name</label>
			<input type="email" class="form-control" id="inputEmail3">
		</div>
		<div class="form-group">
			<label for="inputEmail3" class="control-label">Last Name</label>
			<input type="email" class="form-control" id="inputEmail3">
		</div>
		<div class="form-group">
			<label for="inputEmail3" class="control-label">Phone Number</label>
			<input type="email" class="form-control" id="inputEmail3">
		</div>
	</div>
</div>

<h1>Shipping and Billing Information</h1>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label for="inputEmail3" class="control-label">Address</label>
			<input type="email" class="form-control" id="inputEmail3">
		</div>
		<div class="form-group">
			<label for="inputEmail3" class="control-label">Country</label>
			<select class="form-control">
				<option>Portugal</option>
				<option>Spain</option>
				<option>Netherlands</option>
			</select>
		</div>
		<div class="form-group">
			<label for="inputEmail3" class="control-label">City</label>
			<input type="email" class="form-control" id="inputEmail3">
		</div>
		<div class="form-group">
			<label for="inputEmail3" class="control-label">Postal Code</label>
			<input type="email" class="form-control" id="inputEmail3">
		</div>
	</div>
</div>

<div>
	<button type="submit" class="btn btn-lg btn-success">Complete the Order</button>
</div>

{!! Form::close() !!}
@stop