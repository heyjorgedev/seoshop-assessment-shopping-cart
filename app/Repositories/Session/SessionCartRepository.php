<?php

namespace App\Repositories\Session;

use App\Repositories\Contracts\CartRepositoryContract;
use Symfony\Component\HttpFoundation\Session\SessionInterface as SessionContract;
use App\Models\CartItem;

class SessionCartRepository implements CartRepositoryContract
{
	protected $session;

	public function __construct(SessionContract $session)
	{
		$this->session = $session;
	}

	/**
	 * Check if the Product Exists
	 * @param  int $productId ID of the Product
	 */
	public function hasProduct($productId)
	{
		return $this->session->has($this->getProductSessionKey($productId));
	}

	/**
	 * Get the Product Quantity by Id
	 * @param  [type] $productId [description]
	 * @return [type]            [description]
	 */
	public function getProductQuantity($productId)
	{
		return $this->session->get($this->getProductSessionKey($productId), 0);
	}

	/**
	 * Update the Product by Id with a quantity
	 * @param  int $productId ID of the Product
	 * @param  int $quantity  Quantity of the Product
	 */
	public function updateProduct($productId, $quantity)
	{
		$this->session->put($this->getProductSessionKey($productId), $quantity);
	}

	/**
	 * Add a new Product
	 * @param int $productId ID of the Product
	 * @param int $quantity  Quantity of the Product
	 */
	public function addProduct($productId, $quantity)
	{
		$this->session->put($this->getProductSessionKey($productId), $quantity);
	}

	/**
	 * Remove a Product
	 * @param  int $productId ID of the Product
	 */
	public function removeProduct($productId)
	{
		$this->session->forget($this->getProductSessionKey($productId));
	}

	/**
	 * Get all products in the session
	 * @return array Products array with quantity
	 */
	public function getProducts()
	{
		return $this->session->get('cart.products', []);
	}

	public function getDiscounts()
	{
		// Get Discounts from the Database
		return $this->session->get('cart.discounts', []); // Default value is an empty array
	}

	/**
	 * Add a Discount Coupon
	 * @param int $couponId ID of the Coupon
	 */
	public function addDiscount($couponId)
	{
		$this->session->put($this->getDiscountSessionKey($couponId), 0);
	}

	/**
	 * Remove a Discount Coupon
	 * @param  int $couponId ID of the Coupon
	 */
	public function removeDiscount($couponId)
	{
		// Get the Discounts
		$discounts = $this->getDiscounts();

		// Verify if the Discount Already Exists
		if(array_key_exists($couponId, $discounts))
			return false;

		return true;
	}

	/**
	 * Check if the Product Exists
	 * @param  int $couponId ID of the Coupon
	 * @return [type]           [description]
	 */
	public function discountCouponExists($couponId)
	{
		return true;
	}

	private function getProductSessionKey($productId)
	{
		return 'cart.products.'.$productId;
	}

	private function getDiscountSessionKey($couponId)
	{
		return 'cart.discounts.'.$couponId;
	}
}