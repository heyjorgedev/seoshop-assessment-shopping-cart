<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Contracts\CartServiceContract;
use App\Http\Requests\AddOrRemoveProductToCartRequest;
use App\Http\Requests\AddDiscountToCartRequest;
use App\Http\Requests\CheckoutOrderRequest;

class CartController extends Controller
{
    // Services
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

        session()->flash('message', 'Product was added to the cart');

        // Return the user Back
        return back();
    }

    public function postRemove($id, AddOrRemoveProductToCartRequest $request)
    {
        // Get the Quantity from the POST request.
        $quantity = $request->has('quantity') ? $request->get('quantity') : null;

        // Call the Cart Service to remove this item from the Cart
        $this->cartService->removeProduct($id, $quantity);
        
        if(is_null($quantity))
            session()->flash('message', 'Product was removed from the cart');

        // Return the user Back
        return back();
    }

    public function postAddDiscount(AddDiscountToCartRequest $request)
    {
        try
        {
            // Tries to add a coupon to the cart
            $this->cartService->addCouponByCode($request->get('code'));
            session()->flash('message', $request->get('code').' was added to the cart');
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
            // try to remove a coupon from the cart
            $this->cartService->removeCoupon($id);
            session()->flash('message', 'Coupon removed from the cart');
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
        // if($this->cartService->getProductsCount() == 0)
        //     return redirect()->action('CartController@getIndex');

        $cart = $this->cartService->get();
        return view('cart.checkout', compact('cart'));
    }

    public function postCompleted(CheckoutOrderRequest $request)
    {
        $requestParams = $request->only(
            'email', 
            'firstName', 
            'lastName', 
            'shippingAddress', 
            'shippingCountry', 
            'shippingCity',
            'shippingPostalCode'
        );

        $cart = $this->cartService->get();
        // Here we could add the order to the datastore

        // Clear the Cart to start a new Order
        $this->cartService->clear();

        return view('cart.completed', compact('cart', 'requestParams'));
    }
} 
