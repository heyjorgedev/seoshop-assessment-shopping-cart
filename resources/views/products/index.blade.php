@extends ('layouts.main')
@section('content')
<div class="row">

	@forelse($products as $product)
    	@include('products.partials.product-listitem', ['product' => $product])
	@empty
		<h1>Products</h1>
    	<p>There are no Products.</p>
	@endforelse

</div>
@stop