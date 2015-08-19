<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Contracts\CartServiceContract;
use App\Http\Requests\AddProductToCartRequest;
use App\Http\Requests\AddDiscountToCartRequest;

class CartController extends Controller
{
    protected $cartRepo;
	protected $cartService;

	public function __construct(CartServiceContract $cartService)
	{
        $this->cartService = $cartService;
	}

    public function getIndex()
    {
        // Get all cart items (products, discounts, total) from the Cart Service
    	$cart = $this->cartService->get();

        // Return it to the view
    	return view('cart.index', compact('cart'));
    }

    public function postAdd($id, AddProductToCartRequest $request)
    {
        // Get the Quantity from the POST request.
        $quantity = $request->has('quantity') ? $request->get('quantity') : 1;

        // Call the Cart Service to add this item to the Cart
        $this->cartService->addProduct($id, $quantity);

        // Return the user Back
        return back();
    }

    public function postRemove($id, AddProductToCartRequest $request)
    {
        // Get the Quantity from the POST request.
        $quantity = $request->has('quantity') ? $request->get('quantity') : 1;

        // Call the Cart Service to remove this item from the Cart
        $this->cartService->removeProduct($id, $quantity);

        // Return the user Back
        return back();
    }

    public function postAddDiscount(AddDiscountToCartRequest $request)
    {
        // $quantity = $request->has('quantity') ? $request->get('quantity') : 1;
        // $this->cartRepo->add($id, $quantity);
        return back()->withErrors([ 'message' => 'Teste']);
    }

    public function getCheckout()
    {
        return view('cart.checkout');
    }
}
