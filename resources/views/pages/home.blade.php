@extends ('layouts.main')

@section('content')
<div class="jumbotron presentation">
  <h1>My Tech Store!</h1>
  <p>"My Tech Store" was made as part of an assessement for my application at SEOshop.</p>
  <p>You can check all of our products by clicking the link below and don't be shy, order as many products as you want and create a customer account! (It's all free!!)</p>
  <p><a class="btn btn-success btn-lg" href="{{ action('ProductsController@getIndex') }}" role="button">See Our Products</a></p>
</div>
@stop