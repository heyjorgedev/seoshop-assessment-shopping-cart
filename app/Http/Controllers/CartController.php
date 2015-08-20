<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Contracts\CartServiceContract;
use App\Http\Requests\AddOrRemoveProductToCartRequest;
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

    public function postAdd($id, AddOrRemoveProductToCartRequest $request)
    {
        // Get the Quantity from the POST request.
        $quantity = $request->has('quantity') ? $request->get('quantity') : 1;

        // Call the Cart Service to add this item to the Cart
        $this->cartService->addProduct($id, $quantity);

        // Return the user Back
        return back();
    }

    public function postRemove($id, AddOrRemoveProductToCartRequest $request)
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
        try
        {
            $this->cartService->addCouponByCode($request->get('code'));
            return back();
        }
        catch(\App\Services\Exceptions\CouponAlreadyExistsException $e)
        {
            return back()->withErrors([ 'message' => 'This coupon code is already in your shopping cart.']);
        }
        catch(\App\Services\Exceptions\CouponNotFoundException $e)
        {
            return back()->withErrors([ 'message' => 'The coupon you provided to us is invalid. Try again.']);
        }
        catch(\Exception $e)
        {
            // We should log this because this error is unexpected
            return back()->withErrors([ 'message' => 'Something happened and we were unable to add this coupon code.']);
        }
    }

    public function postRemoveDiscount($id)
    {
        try
        {
            $this->cartService->removeCoupon($id);
            return back();
        }
        catch(\App\Services\Exceptions\CouponNotFoundException $e)
        {
            return back()->withErrors([ 'message' => 'We didnt find this discount coupon in your cart.']);
        }
        catch(\Exception $e)
        {
            // We should log this because this error is unexpected
            return back()->withErrors([ 'message' => 'Something happened and we were unable to remove this discount coupon from your cart.']);
        }
    }

    public function getCheckout()
    {
        return view('cart.checkout');
    }
}
