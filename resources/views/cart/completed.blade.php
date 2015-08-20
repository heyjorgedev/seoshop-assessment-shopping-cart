@extends('layouts.main')

@section('content')

<h1 style="color:green;">Order Completed!</h1>

@include('cart.partials.cart', [ 'products' => $cart, 'preview' => true])

@stop