@extends('layouts.main')

@section('content')
<div>
<a href="{{ action('CartController@getIndex') }}" class="btn btn-default" role="button">&lt; Back to Shopping Cart</a>
</div>

<h1>Review your Order</h1>

<h1>Customer Details</h1>

<div>
	<a href="#" class="btn btn-lg btn-success">Complete the Order</a>
</div>
@stop