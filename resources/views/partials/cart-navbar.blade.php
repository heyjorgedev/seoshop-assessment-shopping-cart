@inject('cartRepo', 'App\Repositories\Contracts\CartRepositoryContract')

<li><a href="{{ action('CartController@getIndex') }}">My Cart <b>({{ $cartRepo->getProductCount() }})</b></a></li>