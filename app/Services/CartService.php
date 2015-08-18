<?php

namespace App\Services;

use App\Services\Contracts\CartServiceContract;
use App\Repositories\Contracts\CartRepositoryContract;
use App\Repositories\Contracts\ProductRepositoryContract;
use App\Repositories\Contracts\CouponRepositoryContract;
use App\Models\CartItem;

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
	 * @return object Object with the cart values
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
	 * @return array Array of App\Models\CartItem
	 */
	public function getProducts()
	{
		// Local "Caching"
		if(isset($this->products)) return $this->products;
		else $this->products = [];

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
			array_push($this->products, $cartItem);
		}

		// Return the Database Products
		return $this->products;
	}

	/**
	 * Get the count of the products in the cart.
	 * @return int Product Count
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
		if(isset($this->discounts)) return $this->discounts;
		else $this->discounts = [];

		// @todo: 

		return $this->discounts;
	}

	/**
	 * Calculates the Total value of the products with the coupon discounts
	 * @return decimal Total Value
	 */
	public function getTotal()
	{
		// Local "Caching" so it doesnt need to recalculate
		if(isset($this->total)) return $this->total;
		else $this->total = 0;

		// Go to each product and sum the value
		foreach($this->getProducts() as $product)
		{
			$this->total += $product->getSubTotal();
		}

		// @todo: Go to each discount and calculate the value
		
		// First the value discounts
		
		// Second the percentage discounts

		// I could use a ternary operator here like the one commented below
		// but its harder to read than a simple IF Statement
		// $this->total = $this->total > 0 ? $this->total : 0;
		if($this->total <= 0)
		{
			// I don't know if it is right but I think the merchant would prefer to give a free item
			// rather than having to pay a negative value
			$this->total = intval(0);
		}

		$this->total = number_format($this->total, 2, '.', '');

		// Return the Formated Number
		return $this->total;
	}

	/**
	 * Add a new Product to the Cart
	 * @param int $productId ID of the Product
	 * @param int $quantity  Quantity of the Product
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
		if($this->productRepository->has($productId) == false) return false; // Throw exception
		
		// Only add the product if it exists in the datastore
		$this->cartRepository->addProduct($productId, $quantity);
		return true;
	}

	/**
	 * Remove the entire Product from the Cart or just a quantity of it
	 * @param  int $productId ID of the Product
	 * @param  int $quantity Quantity of the Product
	 * @return boolean Result
	 */
	public function removeProduct($productId, $quantity)
	{
		if($this->cartRepository->hasProduct($productId) == false) return false;

		$newQuantity = $this->cartRepository->getProductQuantity($productId) - $quantity;

		if($newQuantity <= intval(0))
		{
			$this->cartRepository->removeProduct($productId);
			return true;
		}

		$this->cartRepository->updateProduct($productId, $newQuantity);
		return true;
	}

	public function addCoupon($couponId)
	{
		if($this->cartRepository->hasDiscountCoupon($couponId))
		{
			return false;
		}

		if($this->couponRepository->has($couponId))
		{
			$this->cartRepository->addDiscountCoupon($couponId);
			return true;
		}
	}

	public function removeCoupon($couponId)
	{
		if($this->cartRepository->hasDiscountCoupon($couponId))
		{
			$this->cartRepository->removeDiscountCoupon($couponId);
			return true;
		}

		return false;
	}
}