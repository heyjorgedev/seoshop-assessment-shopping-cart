@extends('layouts.main')

@section('content')

<h1 style="color:green;margin-bottom:30px;">Your Order was Completed!</h1>

@include('cart.partials.cart', [ 'products' => $cart, 'preview' => true])

@stop