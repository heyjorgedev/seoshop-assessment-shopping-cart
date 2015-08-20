@extends('layouts.main')

@section('content')
<div>
<a href="{{ action('CartController@getIndex') }}" class="btn btn-default" role="button">&lt; Back to Shopping Cart</a>
</div>

<h1>Review your order</h1>
<div>
	@include('cart.partials.cart', ['preview' => true])
</div>

<div>
@if($errors->has())
<ul style="color:red;">
	@foreach($errors->all() as $error)
	<li>{{ $error }}</li>	
	@endforeach
</ul>
@endif
</div>
{!! Form::open([ 'action' => 'CartController@postCompleted']) !!}
<h1>Customer Details</h1>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			{!! Form::label('email', 'Email*') !!}
			{!! Form::email('email', null, [ 'class' => 'form-control']) !!}
		</div>
		<div class="form-group">
			{!! Form::label('title', 'Title*') !!}
			<select name="title" class="form-control">
				<option value="Mr">Mr</option>
				<option value="Mrs">Mrs</option>
				<option value="Miss">Miss</option>
			</select>
		</div>
		<div class="form-group">
			{!! Form::label('firstName', 'First Name*') !!}
			{!! Form::text('firstName', null, [ 'class' => 'form-control']) !!}
		</div>
		<div class="form-group">
			{!! Form::label('lastName', 'Last Name*') !!}
			{!! Form::text('lastName', null, [ 'class' => 'form-control']) !!}
		</div>
		<div class="form-group">
			{!! Form::label('phoneNumber', 'Phone Number') !!}
			{!! Form::text('phoneNumber', null, [ 'class' => 'form-control']) !!}
		</div>
	</div>
</div>

<h1>Shipping and Billing Information</h1>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			{!! Form::label('shippingAddress', 'Shipping Address*') !!}
			{!! Form::text('shippingAddress', null, [ 'class' => 'form-control']) !!}
		</div>
		<div class="form-group">
			{!! Form::label('shippingCountry', 'Country*') !!}
			<select name="shippingCountry" class="form-control">
				<option value="Portugal">Portugal</option>
				<option value="Spain">Spain</option>
				<option value="Netherlands">Netherlands</option>
			</select>
		</div>
		<div class="form-group">
			{!! Form::label('shippingCity', 'City*') !!}
			{!! Form::text('shippingCity', null, [ 'class' => 'form-control']) !!}
		</div>
		<div class="form-group">
			{!! Form::label('shippingPostalCode', 'Postal Code*') !!}
			{!! Form::text('shippingPostalCode', null, [ 'class' => 'form-control']) !!}
		</div>
	</div>
</div>

<div>
	<button type="submit" class="btn btn-lg btn-success">Complete the Order</button>
</div>

{!! Form::close() !!}
@stop