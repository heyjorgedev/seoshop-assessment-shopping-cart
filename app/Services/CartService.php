<?php

namespace App\Services;

use App\Services\Contracts\CartServiceContract;
use App\Repositories\Contracts\CartRepositoryContract;
use App\Repositories\Contracts\ProductRepositoryContract;
use App\Repositories\Contracts\CouponRepositoryContract;
use App\Models\Cart\CartItem;
use App\Models\Cart\CartDiscount;

/**
 * This layer is responsible to manage the cart logic like:
 *  - Add Products
 *  - Add Discounts
 *  - Calculate the Total Amount
 *
 * And it was also made to decouple the Repository so that 
 * we can switch the simple implementation and it will just work!
 */
class CartService implements CartServiceContract
{
	// Repositories
	protected $cartRepository;
	protected $productRepository;

	// Used like a Thread Caching
	// just for this request
	// with this I can reduce the DataStore calls
	// and processing
	private $products;
	private $discounts;
	private $total;
	private $productsCount;

	public function __construct(CartRepositoryContract $cartRepository, ProductRepositoryContract $productRepository, CouponRepositoryContract $couponRepository)
	{
		$this->cartRepository = $cartRepository;
		$this->productRepository = $productRepository;
		$this->couponRepository = $couponRepository;
	}

	/**
	 * Get Cart Products, Discounts and Total
	 * @return object
	 */
	public function get()
	{
		return (object) [
			'products'	=> $this->getProducts(),
			'discounts'	=> $this->getDiscounts(),
			'total'		=> $this->getTotal()
		];
	}

	/**
	 * Get the Cart current products mapped with the ProductRepository
	 * @return array
	 */
	public function getProducts()
	{
		// Local "Caching"
		$productsArray = [];

		// Get the Session Products
		$cartProducts = $this->cartRepository->getProducts();
		if(empty($cartProducts)) return $cartProducts;
		
		// Get the Products from the Database by range of IDs
		$products = $this->productRepository->getIn(array_keys($cartProducts));
		if(empty($products)) return $products;

		// Loop Products and Map to the CartItem Object
		foreach($products as $product)
		{
			$cartItem = CartItem::create($product->id, $product->title, $cartProducts[$product->id], $product->price);
			array_push($productsArray, $cartItem);
		}

		// Return the Database Products
		return $productsArray;
	}

	/**
	 * Get the count of the products in the cart.
	 * @return int
	 */
	public function getProductsCount()
	{
		// Local "Caching"
		if(isset($this->productsCount)) return $this->productsCount;
		else $this->productsCount = 0;

		// Go to All the Products and Sum the Quantity
		foreach($this->getProducts() as $product)
		{
			$this->productsCount += intval($product->quantity);
		}

		return $this->productsCount;
	}

	/**
	 * Get the cart current discounts mapped with the CouponRepository
	 * @return array 
	 */
	public function getDiscounts()
	{
		$discountsArray = [];

		$coupons = $this->couponRepository->getIn($this->cartRepository->getDiscounts());

		foreach($coupons as $coupon)
		{
			$cartDiscount = CartDiscount::create($coupon->id, $coupon->code, $coupon->discount, $coupon->is_percentage);
			array_push($discountsArray, $cartDiscount);
		}

		return $discountsArray;
	}

	/**
	 * Calculates the Total value of the products with the coupon discounts
	 * @return decimal
	 */
	public function getTotal()
	{
		// Initialize the Variable as Zero.
		$total = 0;

		// Go to each product and sum the value
		foreach($this->getProducts() as $product)
		{
			$total += $product->getSubTotal();
		}

		// Get the Discounts
		$discounts = $this->getDiscounts();
		
		// First calculate the value discounts
		foreach($discounts as $discount)
		{
			if($discount->isPercentage == false)
			{
				$total -= $discount->value;
			}
		}

		// Second calculate the percentage discounts
		foreach($discounts as $discount)
		{
			if($discount->isPercentage)
			{
				$total -= ($total * ($discount->value / 100));
			}
		}

		// I could use a ternary operator here like the one commented below
		// but its harder to read than a simple IF Statement
		// $total = $total > 0 ? $total : 0;
		if($total <= 0)
		{
			// I don't know if it is right but I think the merchant would prefer to give a free item
			// rather than having to pay a negative value
			$total = intval(0);
		}

		$total = number_format($total, 2, '.', '');

		// Return the Formated Number
		return $total;
	}

	/**
	 * Add a new Product to the Cart
	 * @param int $productId
	 * @throws \App\Services\Exceptions\ProductNotFoundException if the product is not found in the datastore
	 * @param int $quantity
	 */
	public function addProduct($productId, $quantity)
	{
		if($this->cartRepository->hasProduct($productId))
		{
			$newQuantity = $this->cartRepository->getProductQuantity($productId) + $quantity;
			$this->cartRepository->updateProduct($productId, $newQuantity);
			return true;
		}

		// Verify if product exists
		if($this->productRepository->has($productId) == false)
			throw new \App\Services\Exceptions\ProductNotFoundException();
		
		// Only add the product if it exists in the datastore
		$this->cartRepository->addProduct($productId, $quantity);
		return true;
	}

	/**
	 * Remove the entire Product from the Cart or just a quantity of it
	 * @param  int $productId
	 * @param  int $quantity
	 * @return boolean
	 */
	public function removeProduct($productId, $quantity)
	{
		if($this->cartRepository->hasProduct($productId) == false)
			throw new \App\Services\Exceptions\ProductNotFoundException();

		if(is_null($quantity))
		{
			$this->cartRepository->removeProduct($productId);

			if($this->getProductsCount() == 0)
				$this->cartRepository->clearDiscountCoupons();

			return true;
		}

		$newQuantity = $this->cartRepository->getProductQuantity($productId) - $quantity;

		if($newQuantity <= intval(0))
		{
			$this->cartRepository->removeProduct($productId);

			if($this->getProductsCount() == 0)
				$this->cartRepository->clearDiscountCoupons();

			return true;
		}

		$this->cartRepository->updateProduct($productId, $newQuantity);
		return true;
	}

	/**
	 * Add a Discount Coupon to the Cart
	 * @param int $couponId
	 * @throws \App\Services\Exceptions\CouponAlreadyExistsException if the coupon already exists in the datastore
	 * @throws \App\Services\Exceptions\CouponNotFoundException if the coupon is not found in the datastore
	 * @return boolean
	 */
	public function addCoupon($couponId)
	{
		// Verify if the Coupon already exists
		if($this->cartRepository->hasDiscountCoupon($couponId))
		{
			// If it does, it violates the rule and Exception should be thrown
			throw new \App\Services\Exceptions\CouponAlreadyExistsException();
		}

		// Verify if it exsits in the datastore
		if($this->couponRepository->has($couponId))
		{
			// Add the discount and return true as a Result
			$this->cartRepository->addDiscountCoupon($couponId);
			return true;
		}

		// Throw an Exception if we can't find the Coupon in the Datastore
		throw new \App\Services\Exceptions\CouponNotFoundException();
	}

	public function addCouponByCode($couponCode)
	{
		// Get the Coupon from the Datastore
		$coupon = $this->couponRepository->getByCode($couponCode);

		// Verify if Coupon exists and try to add it
		if($coupon != null)
			return $this->addCoupon($coupon->id);

		// Throw an Exception if we can't find the Coupon in the Datastore
		throw new \App\Services\Exceptions\CouponNotFoundException();
	}

	/**
	 * Remove a Discount Coupon from the Cart
	 * @param  int $couponId
	 * @return boolean
	 */
	public function removeCoupon($couponId)
	{
		// Verify if the cart already has the discount coupon
		if($this->cartRepository->hasDiscountCoupon($couponId))
		{	
			// if it has we just need to delete it
			$this->cartRepository->removeDiscountCoupon($couponId);
			return true;
		}

		// If we cant find it we dont need to take any action
		throw new \App\Services\Exceptions\CouponNotFoundException();
	}

	public function clear()
	{
		$this->cartRepository->clearProducts();
		$this->cartRepository->clearDiscountCoupons();
	}
}