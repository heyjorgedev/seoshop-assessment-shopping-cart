@inject('cartService', 'App\Services\Contracts\CartServiceContract')

<li><a href="{{ action('CartController@getIndex') }}">My Cart <b>({{ $cartService->getProductsCount() }})</b></a></li>