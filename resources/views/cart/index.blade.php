@extends('layouts.main')

@section('content')
	
	<h1 style="margin-bottom: 30px;">My Cart</h1>

	@include('cart.partials.validation')
	@include('cart.partials.cart', ['preview' => false])
	
@stop

@section('scripts')
<script>
	$('.numeric').numeric(1);
</script>
@stop