<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\CartRepositoryContract as CartRepository;
use App\Http\Requests\AddProductToCartRequest;
use App\Http\Requests\AddDiscountToCartRequest;

class CartController extends Controller
{
	protected $cartRepo;

	public function __construct(CartRepository $cartRepo)
	{
		$this->cartRepo = $cartRepo;
	}

    public function getIndex()
    {
        // Get all cart items (products, discounts, total) from the Repository
    	$cart = $this->cartRepo->get();

        // Return it to the view
    	return view('cart.index', compact('cart'));
    }

    public function postAdd($id, AddProductToCartRequest $request)
    {
        // Get the Quantity from the POST request.
        $quantity = $request->has('quantity') ? $request->get('quantity') : 1;

        // Call the Repository to add this item to the Cart
        $this->cartRepo->add($id, $quantity);

        // Return the user Back
        return back();
    }

    public function postRemove($id, AddProductToCartRequest $request)
    {
        // Get the Quantity from the POST request.
        $quantity = $request->has('quantity') ? $request->get('quantity') : 1;

        // Call the Repository to add this item to the Cart
        $this->cartRepo->remove($id, $quantity);

        // Return the user Back
        return back();
    }

    public function postAddDiscount($id, AddDiscountToCartRequest $request)
    {
        $quantity = $request->has('quantity') ? $request->get('quantity') : 1;
        $this->cartRepo->add($id, $quantity);
        return back();
    }

    public function getCheckout()
    {
        return view('cart.checkout');
    }
}
