<div class="col-sm-6 col-md-3">
  
  <div class="thumbnail">
    
    @include('products.partials.product-image', [ 'url' => $product->image_url, 'title' => $product->title ])

    <div class="caption">
      
      <h3>{{ $product->title }}</h3>
      
      <p><b>{{ $product->price }}€</b></p>
      
      <p>
        {!! Form::open([ 'url' => action('CartController@postAdd', ['id' => $product->id])]) !!}
          <a href="{{ action('ProductsController@getDetails', ['id' => $product->id]) }}" class="btn btn-primary" role="button">Details</a>
          <button type="submit" class="btn btn-success" href="#">Add to Cart</button>
        {!! Form::close() !!}
      </p>

    </div>

  </div>

</div>